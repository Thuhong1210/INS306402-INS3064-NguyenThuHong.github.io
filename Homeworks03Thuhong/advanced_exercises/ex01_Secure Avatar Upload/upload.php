<?php
declare(strict_types=1);
session_start();

/* -----------------------------
   Config
------------------------------ */
const MAX_BYTES = 2 * 1024 * 1024; // 2MB
const UPLOAD_DIR = __DIR__ . '/uploads';

$ALLOWED_MIME = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
];

/* -----------------------------
   Helpers (PHPMD-friendly: no superglobals inside helpers)
------------------------------ */
function escape(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

function flashSet(array &$session, string $key, mixed $value): void {
    $session['_flash'] ??= [];
    $session['_flash'][$key] = $value;
}

function flashGet(array &$session, string $key, mixed $default = null): mixed {
    if (!isset($session['_flash']) || !array_key_exists($key, $session['_flash'])) return $default;
    $val = $session['_flash'][$key];
    unset($session['_flash'][$key]);
    return $val;
}

function ensureUploadDir(string $dir): bool {
    if (is_dir($dir)) return is_writable($dir);
    if (!mkdir($dir, 0755, true)) return false;
    return is_writable($dir);
}

function randomFileName(string $ext): string {
    // 32 hex chars
    $token = bin2hex(random_bytes(16));
    return $token . '.' . $ext;
}

/* -----------------------------
   POST (handle upload) + PRG
------------------------------ */
$session =& $_SESSION; // single access point

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $files = $_FILES; // single access point

    // Basic checks: file field exists
    if (!isset($files['avatar']) || !is_array($files['avatar'])) {
        flashSet($session, 'error', 'No file was uploaded.');
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    $f = $files['avatar'];

    // Upload error handling
    $err = (int)($f['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($err !== UPLOAD_ERR_OK) {
        $msg = match ($err) {
            UPLOAD_ERR_NO_FILE => 'Please choose an image to upload.',
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File is too large (max 2MB).',
            UPLOAD_ERR_PARTIAL => 'Upload was interrupted. Please try again.',
            default => 'Upload failed. Please try again.',
        };
        flashSet($session, 'error', $msg);
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    // Must be a real uploaded file
    $tmpPath = (string)($f['tmp_name'] ?? '');
    if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
        flashSet($session, 'error', 'Invalid upload.');
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    // Size check (strict)
    $size = (int)($f['size'] ?? 0);
    if ($size <= 0 || $size > MAX_BYTES) {
        flashSet($session, 'error', 'File size must be between 1 byte and 2MB.');
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    // Validate by MIME (server-side, real content)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string)$finfo->file($tmpPath);

    global $ALLOWED_MIME;
    if (!array_key_exists($mime, $ALLOWED_MIME)) {
        flashSet($session, 'error', 'Only JPG and PNG images are allowed.');
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    // (Optional but strong) Ensure it’s a real image
    $imgInfo = @getimagesize($tmpPath);
    if ($imgInfo === false) {
        flashSet($session, 'error', 'The uploaded file is not a valid image.');
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    // Validate extension based on original name (secondary check)
    $origName = (string)($f['name'] ?? '');
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png'];
    if ($ext === 'jpeg') $ext = 'jpg';
    if (!in_array($ext, $allowedExts, true)) {
        flashSet($session, 'error', 'Only JPG and PNG images are allowed (by extension).');
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    // Decide final extension by MIME (source of truth)
    $finalExt = $ALLOWED_MIME[$mime];

    // Ensure upload dir exists and is writable
    if (!ensureUploadDir(UPLOAD_DIR)) {
        flashSet($session, 'error', 'Server upload folder is not writable.');
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    // Rename to prevent overwrites
    $newName = randomFileName($finalExt);
    $destPath = UPLOAD_DIR . '/' . $newName;

    // Move securely
    if (!move_uploaded_file($tmpPath, $destPath)) {
        flashSet($session, 'error', 'Could not save the uploaded file.');
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    // Store relative path for display
    $session['avatar_rel'] = 'uploads/' . $newName;

    flashSet($session, 'success', 'Avatar uploaded successfully!');
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

/* -----------------------------
   GET (render)
------------------------------ */
$success = (string)flashGet($session, 'success', '');
$error   = (string)flashGet($session, 'error', '');
$avatarRel = isset($session['avatar_rel']) ? (string)$session['avatar_rel'] : '';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Secure Avatar Upload</title>
<style>
:root{
  --bg:#f6f7fb; --card:#fff; --text:#111827; --muted:#6b7280; --border:#e5e7eb;
  --primary:#2563eb; --primaryHover:#1e40af;
  --dangerBg:#fef2f2; --dangerBorder:#fecaca; --dangerText:#b91c1c;
  --okBg:#ecfdf5; --okBorder:#bbf7d0; --okText:#047857;
  --shadow:0 12px 30px rgba(0,0,0,.06); --radius:14px;
}
*{box-sizing:border-box}
body{
  margin:0;
  font-family:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial;
  background:
    radial-gradient(900px 450px at 15% 10%, rgba(37,99,235,.10), transparent 55%),
    radial-gradient(900px 450px at 90% 20%, rgba(59,130,246,.10), transparent 60%),
    var(--bg);
  color:var(--text);
  padding:40px 18px;
}
.wrap{max-width:860px;margin:0 auto}
.header{text-align:center;margin-bottom:18px}
.header h1{margin:0 0 6px;font-size:28px}
.header p{margin:0;color:var(--muted);line-height:1.5}

.card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  overflow:hidden;
}
.body{padding:22px}

.alert{
  border-radius:12px;
  padding:12px 14px;
  border:1px solid transparent;
  margin:0 0 16px;
  font-size:14px;
}
.alert-ok{background:var(--okBg);border-color:var(--okBorder);color:var(--okText)}
.alert-danger{background:var(--dangerBg);border-color:var(--dangerBorder);color:var(--dangerText)}

.grid{display:grid;gap:14px}
@media(min-width:760px){.grid{grid-template-columns: 1.2fr .8fr}}
label{display:block;font-size:13px;font-weight:650;margin:0 0 6px}
.help{margin:6px 0 0;color:var(--muted);font-size:12px;line-height:1.45}

input[type="file"]{
  width:100%;
  padding:10px 12px;
  border-radius:10px;
  border:1px solid var(--border);
  background:#fff;
  font-size:14px;
}

.actions{
  display:flex;justify-content:space-between;align-items:center;gap:12px;
  margin-top:16px;padding-top:16px;border-top:1px solid var(--border);
}
.btn{
  border:none;border-radius:12px;background:var(--primary);color:#fff;font-weight:800;
  padding:10px 16px;cursor:pointer;transition:.15s ease;min-width:160px;
}
.btn:hover{background:var(--primaryHover)}
.note{color:var(--muted);font-size:12px;margin:0}

.preview{
  border:1px solid var(--border);
  border-radius:12px;
  background:#fafafa;
  padding:14px;
}
.avatar{
  width:120px;height:120px;border-radius:999px;
  border:1px solid var(--border);
  object-fit:cover;background:#fff;
  display:block;margin:0 auto 10px;
}
.preview h3{margin:0 0 6px;font-size:14px;text-align:center}
.preview p{margin:0;color:var(--muted);font-size:12px;text-align:center;line-height:1.4}

.footer{margin-top:14px;text-align:center;color:var(--muted);font-size:12px}
code{background:#f3f4f6;padding:2px 6px;border-radius:6px}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>Secure Avatar Upload</h1>
    <p>Only JPG/PNG • Max 2MB • Strict MIME validation • Safe renaming</p>
  </div>

  <div class="card">
    <div class="body">

      <?php if ($success !== ''): ?>
        <div class="alert alert-ok" role="status" aria-live="polite"><strong><?= escape($success) ?></strong></div>
      <?php endif; ?>

      <?php if ($error !== ''): ?>
        <div class="alert alert-danger" role="alert" aria-live="polite"><strong><?= escape($error) ?></strong></div>
      <?php endif; ?>

      <div class="grid">
        <div>
          <form method="post" action="" enctype="multipart/form-data">
            <label for="avatar">Choose an avatar image</label>
            <input id="avatar" name="avatar" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required>

            <p class="help">
              Allowed types: <code>jpg</code>, <code>png</code><br>
              Maximum size: <code>2MB</code><br>
              Files are renamed to prevent overwrites.
            </p>

            <div class="actions">
              <p class="note">Tip: use a square image for best results</p>
              <button class="btn" type="submit">Upload Avatar</button>
            </div>
          </form>
        </div>

        <div class="preview">
          <h3>Preview</h3>
          <?php if ($avatarRel !== '' && is_file(__DIR__ . '/' . $avatarRel)): ?>
            <img class="avatar" src="<?= escape($avatarRel) ?>" alt="Uploaded avatar">
            <p>Saved as:<br><code><?= escape(basename($avatarRel)) ?></code></p>
          <?php else: ?>
            <img class="avatar" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Crect width='100%25' height='100%25' fill='%23ffffff'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%239ca3af' font-family='Arial' font-size='12'%3ENo avatar%3C/text%3E%3C/svg%3E" alt="No avatar">
            <p>No avatar uploaded yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <div class="footer">© <?= (int)date('Y') ?> Secure Upload Demo</div>
    </div>
  </div>
</div>
</body>
</html>
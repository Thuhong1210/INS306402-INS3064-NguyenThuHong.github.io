<?php
declare(strict_types=1);
session_start();

/* ---------- helpers (no superglobals inside helpers) ---------- */
function escape(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

function oldValue(array $old, string $key, string $default = ''): string {
    if (!array_key_exists($key, $old)) return $default;
    $val = $old[$key];
    if (is_array($val)) return $default;
    return escape((string)$val);
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

$session =& $_SESSION;

// allow reset
if (isset($_GET['reset'])) {
    unset($session['wizard_step1'], $session['wizard_done']);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

/* ---------- POST (validate + store + redirect) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post = $_POST;

    $firstName = trim((string)($post['first_name'] ?? ''));
    $lastName  = trim((string)($post['last_name'] ?? ''));
    $email     = trim((string)($post['email'] ?? ''));

    $errors = [];
    if ($firstName === '') $errors[] = 'First name is required.';
    if ($lastName === '')  $errors[] = 'Last name is required.';
    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email must be a valid email address.';
    }

    $old = ['first_name' => $firstName, 'last_name' => $lastName, 'email' => $email];

    if (!empty($errors)) {
        flashSet($session, 'errors', $errors);
        flashSet($session, 'old', $old);
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    // persist step1 data
    $session['wizard_step1'] = $old;

    // PRG to step2
    header('Location: step2.php');
    exit;
}

/* ---------- GET (render) ---------- */
$errors = flashGet($session, 'errors', []);
$old    = flashGet($session, 'old', []);

if (!is_array($errors)) $errors = [];
if (!is_array($old)) $old = [];

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registration Wizard - Step 1</title>
<style>
:root{
  --bg:#f6f7fb; --card:#fff; --text:#111827; --muted:#6b7280; --border:#e5e7eb;
  --primary:#2563eb; --primaryHover:#1e40af;
  --dangerBg:#fef2f2; --dangerBorder:#fecaca; --dangerText:#b91c1c;
  --shadow:0 12px 30px rgba(0,0,0,.06); --radius:14px;
}
*{box-sizing:border-box}
body{
  margin:0; font-family:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial;
  background: radial-gradient(900px 450px at 15% 10%, rgba(37,99,235,.10), transparent 55%),
              radial-gradient(900px 450px at 90% 20%, rgba(59,130,246,.10), transparent 60%),
              var(--bg);
  color:var(--text); padding:40px 18px;
}
.wrap{max-width:860px;margin:0 auto}
.header{text-align:center;margin-bottom:18px}
.header h1{margin:0 0 6px;font-size:28px}
.header p{margin:0;color:var(--muted);line-height:1.5}
.card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden}
.body{padding:22px}
.progress{display:flex;gap:10px;align-items:center;margin:0 0 16px}
.pill{padding:6px 10px;border-radius:999px;border:1px solid var(--border);font-size:12px;color:var(--muted);background:#fafafa}
.pill.active{border-color:rgba(37,99,235,.35);color:#1e3a8a;background:rgba(37,99,235,.08)}
.alert{border-radius:12px;padding:12px 14px;border:1px solid var(--dangerBorder);background:var(--dangerBg);color:var(--dangerText);margin:0 0 16px;font-size:14px}
.alert ul{margin:8px 0 0 18px}
.grid{display:grid;gap:14px}
@media(min-width:760px){.grid{grid-template-columns:1fr 1fr}.span-2{grid-column:1/-1}}
label{display:block;font-size:13px;font-weight:650;margin:0 0 6px}
.req{color:var(--primary);font-weight:800}
input{
  width:100%;padding:10px 12px;border-radius:10px;border:1px solid var(--border);
  outline:none;font-size:14px;transition:.15s ease;background:#fff;
}
input:focus{border-color:rgba(37,99,235,.65);box-shadow:0 0 0 4px rgba(37,99,235,.12)}
.actions{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:16px;padding-top:16px;border-top:1px solid var(--border)}
.btn{
  border:none;border-radius:12px;background:var(--primary);color:#fff;font-weight:800;
  padding:10px 16px;cursor:pointer;transition:.15s ease;min-width:140px;
}
.btn:hover{background:var(--primaryHover)}
.link{color:var(--muted);font-size:12px;text-decoration:none}
.link:hover{color:#374151}
.footer{text-align:center;color:var(--muted);font-size:12px;margin-top:14px}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>Registration Wizard</h1>
    <p>Step 1 of 2 — Basic information</p>
  </div>

  <div class="card">
    <div class="body">
      <div class="progress">
        <span class="pill active">Step 1</span>
        <span class="pill">Step 2</span>
      </div>

      <?php if (!empty($errors)): ?>
        <div class="alert" role="alert" aria-live="polite">
          <strong>Please fix the following:</strong>
          <ul>
            <?php foreach ($errors as $m): ?>
              <li><?= escape((string)$m) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" action="">
        <div class="grid">
          <div>
            <label for="first_name">First name <span class="req">*</span></label>
            <input id="first_name" name="first_name" type="text" placeholder="e.g. Jane" value="<?= oldValue($old,'first_name') ?>">
          </div>
          <div>
            <label for="last_name">Last name <span class="req">*</span></label>
            <input id="last_name" name="last_name" type="text" placeholder="e.g. Doe" value="<?= oldValue($old,'last_name') ?>">
          </div>
          <div class="span-2">
            <label for="email">Email <span class="req">*</span></label>
            <input id="email" name="email" type="email" placeholder="name@example.com" value="<?= oldValue($old,'email') ?>">
          </div>
        </div>

        <div class="actions">
          <a class="link" href="?reset=1">Reset wizard</a>
          <button class="btn" type="submit">Continue</button>
        </div>
      </form>

      <div class="footer">© <?= (int)date('Y') ?> Wizard • PRG pattern</div>
    </div>
  </div>
</div>
</body>
</html>
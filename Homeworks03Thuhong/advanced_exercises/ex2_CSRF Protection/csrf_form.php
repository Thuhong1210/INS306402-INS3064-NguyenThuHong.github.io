<?php
declare(strict_types=1);
session_start();

/* -----------------------------
   Config
------------------------------ */
const CSRF_SESSION_KEY = 'csrf_token';
const CSRF_COOKIE_NAME = 'csrf_token';
const CSRF_BYTES = 32;          // 32 bytes => 64 hex chars
const CSRF_COOKIE_TTL = 3600;   // 1 hour

/* -----------------------------
   Custom exception (no exit in function)
------------------------------ */
final class CsrfException extends RuntimeException {}

/* -----------------------------
   Helpers (no superglobals inside helpers)
------------------------------ */
function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function generateCsrfToken(): string {
    return bin2hex(random_bytes(CSRF_BYTES));
}

function ensureCsrfToken(
    array &$sessionData,
    array $cookieData,
    bool $isHttps,
    string $cookiePath = '/'
): string {
    $sessionToken = isset($sessionData[CSRF_SESSION_KEY]) ? (string)$sessionData[CSRF_SESSION_KEY] : '';
    $cookieToken  = isset($cookieData[CSRF_COOKIE_NAME]) ? (string)$cookieData[CSRF_COOKIE_NAME] : '';

    // If missing OR mismatch between session and cookie, refresh token
    if ($sessionToken === '' || $cookieToken === '' || !hash_equals($sessionToken, $cookieToken)) {
        $newToken = generateCsrfToken();
        $sessionData[CSRF_SESSION_KEY] = $newToken;

        setcookie(CSRF_COOKIE_NAME, $newToken, [
            'expires'  => time() + CSRF_COOKIE_TTL,
            'path'     => $cookiePath,
            'secure'   => $isHttps,
            'httponly' => false, // double-submit cookie pattern commonly keeps this readable
            'samesite' => 'Lax',
        ]);

        return $newToken;
    }

    return $sessionToken;
}

function verifyCsrfOrThrow(array $sessionData, array $cookieData, array $postData): void {
    $formToken   = isset($postData['csrf_token']) ? (string)$postData['csrf_token'] : '';
    $cookieToken = isset($cookieData[CSRF_COOKIE_NAME]) ? (string)$cookieData[CSRF_COOKIE_NAME] : '';
    $sessionToken = isset($sessionData[CSRF_SESSION_KEY]) ? (string)$sessionData[CSRF_SESSION_KEY] : '';

    if ($formToken === '' || $cookieToken === '' || $sessionToken === '') {
        throw new CsrfException('CSRF token missing.');
    }

    if (!hash_equals($cookieToken, $formToken) || !hash_equals($sessionToken, $formToken)) {
        throw new CsrfException('CSRF token mismatch.');
    }
}

/* -----------------------------
   Read superglobals once
------------------------------ */
$sessionData =& $_SESSION;
$cookieData = $_COOKIE;

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

/* -----------------------------
   Ensure token for GET rendering
------------------------------ */
$csrfToken = ensureCsrfToken($sessionData, $cookieData, $isHttps, '/');

/* -----------------------------
   Form handling
------------------------------ */
$successMessage = '';
$errorMessage = '';
$submittedData = [];

try {
    if ($requestMethod === 'POST') {
        $postData = $_POST;

        // Verify CSRF on every POST
        verifyCsrfOrThrow($sessionData, $cookieData, $postData);

        // Example fields
        $nameValue = trim((string)($postData['name'] ?? ''));
        $emailValue = trim((string)($postData['email'] ?? ''));
        $messageValue = trim((string)($postData['message'] ?? ''));

        $errorsList = [];

        if ($nameValue === '') $errorsList[] = 'Name is required.';
        if ($emailValue === '') {
            $errorsList[] = 'Email is required.';
        } elseif (!filter_var($emailValue, FILTER_VALIDATE_EMAIL)) {
            $errorsList[] = 'Email must be a valid email address.';
        }
        if ($messageValue === '') $errorsList[] = 'Message is required.';

        if (!empty($errorsList)) {
            $errorMessage = implode(' ', $errorsList);
        } else {
            $successMessage = 'Form submitted successfully (CSRF verified).';
            $submittedData = [
                'Name' => $nameValue,
                'Email' => $emailValue,
                'Message' => $messageValue,
            ];

            // Optional: rotate token after successful POST
            $rotatedToken = generateCsrfToken();
            $sessionData[CSRF_SESSION_KEY] = $rotatedToken;
            setcookie(CSRF_COOKIE_NAME, $rotatedToken, [
                'expires'  => time() + CSRF_COOKIE_TTL,
                'path'     => '/',
                'secure'   => $isHttps,
                'httponly' => false,
                'samesite' => 'Lax',
            ]);
            $csrfToken = $rotatedToken;
        }
    }
} catch (CsrfException $csrfError) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo '403 Forbidden: ' . $csrfError->getMessage();
    return; // no exit in function, stop script safely
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CSRF Protected Form (Double Submit Cookie)</title>
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
.wrap{max-width:920px;margin:0 auto}
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
.alert-danger{background:var(--dangerBg);border-color:var(--dangerBorder);color:var(--dangerText)}
.alert-ok{background:var(--okBg);border-color:var(--okBorder);color:var(--okText)}
.grid{display:grid;gap:14px}
@media(min-width:760px){.grid{grid-template-columns:1fr 1fr}.span-2{grid-column:1/-1}}
label{display:block;font-size:13px;font-weight:650;margin:0 0 6px}
.req{color:var(--primary);font-weight:800}
.help{margin:6px 0 0;color:var(--muted);font-size:12px;line-height:1.45}
input,textarea{
  width:100%;padding:10px 12px;border-radius:10px;border:1px solid var(--border);
  background:#fff;color:var(--text);outline:none;transition:.15s ease;font-size:14px;
}
textarea{min-height:120px;resize:vertical}
input:focus,textarea:focus{
  border-color:rgba(37,99,235,.65);
  box-shadow:0 0 0 4px rgba(37,99,235,.12);
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
.kv{
  display:grid;grid-template-columns:140px 1fr;gap:10px 14px;
  padding:12px;border:1px solid var(--border);border-radius:12px;background:#fafafa;margin-top:10px;
}
.k{color:var(--muted);font-size:12px}
.vv{white-space:pre-wrap;font-size:14px}
.footer{margin-top:14px;text-align:center;color:var(--muted);font-size:12px}
code{background:#f3f4f6;padding:2px 6px;border-radius:6px}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>CSRF Protected Form</h1>
    <p>Double Submit Cookie pattern (cookie + hidden field) with session storage and 403 on mismatch.</p>
  </div>

  <div class="card">
    <div class="body">

      <?php if ($errorMessage !== ''): ?>
        <div class="alert alert-danger" role="alert" aria-live="polite">
          <strong><?= escape($errorMessage) ?></strong>
        </div>
      <?php endif; ?>

      <?php if ($successMessage !== ''): ?>
        <div class="alert alert-ok" role="status" aria-live="polite">
          <strong><?= escape($successMessage) ?></strong>
          <?php if (!empty($submittedData)): ?>
            <div class="kv">
              <?php foreach ($submittedData as $keyName => $valueText): ?>
                <div class="k"><?= escape((string)$keyName) ?></div>
                <div class="vv"><?= $keyName === 'Message' ? nl2br(escape((string)$valueText)) : escape((string)$valueText) ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <form method="post" action="">
        <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">

        <div class="grid">
          <div>
            <label for="name">Name <span class="req">*</span></label>
            <input id="name" name="name" type="text" placeholder="e.g. Jane Doe">
          </div>

          <div>
            <label for="email">Email <span class="req">*</span></label>
            <input id="email" name="email" type="email" placeholder="name@example.com">
          </div>

          <div class="span-2">
            <label for="message">Message <span class="req">*</span></label>
            <textarea id="message" name="message" placeholder="Write your message..."></textarea>
            <p class="help">Token generated with <code>bin2hex(random_bytes())</code>. Verification uses <code>hash_equals()</code>.</p>
          </div>
        </div>

        <div class="actions">
          <p class="note">If tokens do not match, the server returns <code>403 Forbidden</code>.</p>
          <button class="btn" type="submit">Submit</button>
        </div>

        <div class="footer">© <?= (int)date('Y') ?> CSRF Demo</div>
      </form>

    </div>
  </div>
</div>
</body>
</html>
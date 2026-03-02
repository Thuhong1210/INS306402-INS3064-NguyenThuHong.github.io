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
   Helpers (no superglobals inside helpers)
------------------------------ */
function escape(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

function forbidden(string $message = 'Forbidden'): void {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $message;
    exit;
}

function generateCsrfToken(): string {
    return bin2hex(random_bytes(CSRF_BYTES));
}

function ensureCsrfToken(array &$session, array $cookie, string $cookiePath = ''): string {
    // If token missing in session or cookie, generate a fresh one
    $sessionToken = isset($session[CSRF_SESSION_KEY]) ? (string)$session[CSRF_SESSION_KEY] : '';
    $cookieToken  = isset($cookie[CSRF_COOKIE_NAME]) ? (string)$cookie[CSRF_COOKIE_NAME] : '';

    if ($sessionToken === '' || $cookieToken === '' || $sessionToken !== $cookieToken) {
        $token = generateCsrfToken();
        $session[CSRF_SESSION_KEY] = $token;

        // Set cookie (Double Submit Cookie)
        $params = [
            'expires'  => time() + CSRF_COOKIE_TTL,
            'path'     => ($cookiePath !== '' ? $cookiePath : '/'),
            'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'), // best effort
            'httponly' => false,  // double-submit requires JS readable in some variants; keep false for compatibility
            'samesite' => 'Lax',
        ];
        setcookie(CSRF_COOKIE_NAME, $token, $params);
        return $token;
    }

    return $sessionToken;
}

function verifyCsrf(array $session, array $cookie, array $post): void {
    $formToken   = isset($post['csrf_token']) ? (string)$post['csrf_token'] : '';
    $cookieToken = isset($cookie[CSRF_COOKIE_NAME]) ? (string)$cookie[CSRF_COOKIE_NAME] : '';
    $sessToken   = isset($session[CSRF_SESSION_KEY]) ? (string)$session[CSRF_SESSION_KEY] : '';

    // Require all tokens present and matching
    if ($formToken === '' || $cookieToken === '' || $sessToken === '') {
        forbidden('403 Forbidden: CSRF token missing.');
    }
    if (!hash_equals($cookieToken, $formToken) || !hash_equals($sessToken, $formToken)) {
        forbidden('403 Forbidden: CSRF token mismatch.');
    }
}

/* -----------------------------
   Read superglobals once
------------------------------ */
$session =& $_SESSION;
$cookie  = $_COOKIE;
$method  = $_SERVER['REQUEST_METHOD'] ?? 'GET';

/* -----------------------------
   CSRF token for GET rendering
------------------------------ */
$csrfToken = ensureCsrfToken($session, $cookie);

/* -----------------------------
   POST handler
------------------------------ */
$success = '';
$error = '';
$submitted = [];

if ($method === 'POST') {
    $post = $_POST;

    // Verify CSRF on every POST
    verifyCsrf($session, $cookie, $post);

    // Example form fields
    $name  = trim((string)($post['name'] ?? ''));
    $email = trim((string)($post['email'] ?? ''));
    $msg   = trim((string)($post['message'] ?? ''));

    $errs = [];
    if ($name === '') $errs[] = 'Name is required.';
    if ($email === '') {
        $errs[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errs[] = 'Email must be a valid email address.';
    }
    if ($msg === '') $errs[] = 'Message is required.';

    if (!empty($errs)) {
        $error = implode(' ', $errs);
    } else {
        $success = 'Form submitted successfully (CSRF verified).';
        $submitted = [
            'Name' => $name,
            'Email' => $email,
            'Message' => $msg,
        ];

        // Optional: rotate token after successful POST
        $newToken = generateCsrfToken();
        $session[CSRF_SESSION_KEY] = $newToken;
        setcookie(CSRF_COOKIE_NAME, $newToken, [
            'expires'  => time() + CSRF_COOKIE_TTL,
            'path'     => '/',
            'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
        $csrfToken = $newToken;
    }
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
.v{white-space:pre-wrap;font-size:14px}
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

      <?php if ($error !== ''): ?>
        <div class="alert alert-danger" role="alert" aria-live="polite">
          <strong><?= escape($error) ?></strong>
        </div>
      <?php endif; ?>

      <?php if ($success !== ''): ?>
        <div class="alert alert-ok" role="status" aria-live="polite">
          <strong><?= escape($success) ?></strong>
          <?php if (!empty($submitted)): ?>
            <div class="kv">
              <?php foreach ($submitted as $k => $v): ?>
                <div class="k"><?= escape((string)$k) ?></div>
                <div class="v"><?= $k === 'Message' ? nl2br(escape((string)$v)) : escape((string)$v) ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <form method="post" action="">
        <!-- Double Submit Cookie: hidden token must match cookie AND session -->
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
            <p class="help">CSRF token is generated using <code>bin2hex(random_bytes())</code>.</p>
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
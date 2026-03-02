<?php
declare(strict_types=1);
session_start();

/* ---------- helpers ---------- */
function escape(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

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
function oldValue(array $old, string $key, string $default = ''): string {
    if (!array_key_exists($key, $old)) return $default;
    $val = $old[$key];
    if (is_array($val)) return $default;
    return escape((string)$val);
}
function isChecked(array $old, string $key, string $value): string {
    if (!array_key_exists($key, $old)) return '';
    $val = $old[$key];
    if (is_array($val)) return in_array($value, $val, true) ? 'checked' : '';
    return ((string)$val === $value) ? 'checked' : '';
}

/* ---------- flow control ---------- */
$session =& $_SESSION;

// allow back/reset
if (isset($_GET['reset'])) {
    unset($session['wizard_step1'], $session['wizard_done']);
    header('Location: step1.php');
    exit;
}

// must have step1 data
$step1 = $session['wizard_step1'] ?? null;
if (!is_array($step1)) {
    header('Location: step1.php');
    exit;
}

/* ---------- POST (final submit) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post = $_POST;

    // Hidden inputs (step1 carried forward)
    $firstName = trim((string)($post['first_name'] ?? ''));
    $lastName  = trim((string)($post['last_name'] ?? ''));
    $email     = trim((string)($post['email'] ?? ''));

    // Step2 fields
    $username  = trim((string)($post['username'] ?? ''));
    $password  = (string)($post['password'] ?? '');
    $plan      = (string)($post['plan'] ?? '');
    $terms     = (string)($post['terms'] ?? ''); // yes when checked

    $errors = [];

    // Validate step1 again (defensive)
    if ($firstName === '' || $lastName === '' || $email === '') {
        $errors[] = 'Step 1 data is missing. Please restart the wizard.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email must be a valid email address.';
    }

    // Validate step2
    if ($username === '') $errors[] = 'Username is required.';
    if (strlen($username) < 4) $errors[] = 'Username must be at least 4 characters.';
    if ($password === '') $errors[] = 'Password is required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($plan === '') $errors[] = 'Please select a plan.';
    if ($terms !== 'yes') $errors[] = 'You must accept the terms.';

    $old = [
        'username' => $username,
        'plan' => $plan,
        'terms' => $terms,
    ];

    if (!empty($errors)) {
        flashSet($session, 'errors2', $errors);
        flashSet($session, 'old2', $old);
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    // Save final result (demo)
    $session['wizard_done'] = [
        'First name' => $firstName,
        'Last name' => $lastName,
        'Email' => $email,
        'Username' => $username,
        'Plan' => $plan,
    ];

    // PRG done
    header('Location: step2.php?done=1');
    exit;
}

/* ---------- GET (render) ---------- */
$errors2 = flashGet($session, 'errors2', []);
$old2    = flashGet($session, 'old2', []);
$done = isset($_GET['done']) && $_GET['done'] === '1';
$final = $done ? ($session['wizard_done'] ?? null) : null;

if (!is_array($errors2)) $errors2 = [];
if (!is_array($old2)) $old2 = [];

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registration Wizard - Step 2</title>
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
.alert{
  border-radius:12px;padding:12px 14px;border:1px solid transparent;margin:0 0 16px;font-size:14px
}
.alert ul{margin:8px 0 0 18px}
.alert-danger{background:var(--dangerBg);border-color:var(--dangerBorder);color:var(--dangerText)}
.alert-ok{background:var(--okBg);border-color:var(--okBorder);color:var(--okText)}
.grid{display:grid;gap:14px}
@media(min-width:760px){.grid{grid-template-columns:1fr 1fr}.span-2{grid-column:1/-1}}
label{display:block;font-size:13px;font-weight:650;margin:0 0 6px}
.req{color:var(--primary);font-weight:800}
input,select{
  width:100%;padding:10px 12px;border-radius:10px;border:1px solid var(--border);
  outline:none;font-size:14px;transition:.15s ease;background:#fff;
}
input:focus,select:focus{border-color:rgba(37,99,235,.65);box-shadow:0 0 0 4px rgba(37,99,235,.12)}
.row{
  display:flex;gap:14px;flex-wrap:wrap;align-items:center;
  padding:10px 12px;border:1px solid var(--border);border-radius:10px;background:#fafafa;
}
.row label{margin:0;font-weight:650;font-size:14px}
.row input{width:auto}
.actions{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:16px;padding-top:16px;border-top:1px solid var(--border)}
.btn{
  border:none;border-radius:12px;background:var(--primary);color:#fff;font-weight:800;
  padding:10px 16px;cursor:pointer;transition:.15s ease;min-width:160px;
}
.btn:hover{background:var(--primaryHover)}
.link{color:var(--muted);font-size:12px;text-decoration:none}
.link:hover{color:#374151}
.kv{
  display:grid;grid-template-columns:140px 1fr;gap:10px 14px;
  padding:12px;border:1px solid var(--border);border-radius:12px;background:#fafafa;margin-top:10px;
}
.k{color:var(--muted);font-size:12px}
.v{font-size:14px}
.footer{text-align:center;color:var(--muted);font-size:12px;margin-top:14px}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>Registration Wizard</h1>
    <p>Step 2 of 2 — Account setup</p>
  </div>

  <div class="card">
    <div class="body">
      <div class="progress">
        <span class="pill">Step 1</span>
        <span class="pill active">Step 2</span>
      </div>

      <?php if (!empty($errors2)): ?>
        <div class="alert alert-danger" role="alert" aria-live="polite">
          <strong>Please fix the following:</strong>
          <ul>
            <?php foreach ($errors2 as $m): ?>
              <li><?= escape((string)$m) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($done && is_array($final)): ?>
        <div class="alert alert-ok" role="status" aria-live="polite">
          <strong>Registration completed successfully!</strong>
          <div class="kv">
            <?php foreach ($final as $k => $v): ?>
              <div class="k"><?= escape((string)$k) ?></div>
              <div class="v"><?= escape((string)$v) ?></div>
            <?php endforeach; ?>
          </div>
          <div class="actions" style="border-top:none;padding-top:10px;margin-top:10px">
            <a class="link" href="step1.php?reset=1">Start over</a>
            <a class="link" href="step1.php">Back to Step 1</a>
          </div>
        </div>
      <?php endif; ?>

      <form method="post" action="">
        <!-- Hidden Inputs (Step 1 data carried forward) -->
        <input type="hidden" name="first_name" value="<?= escape((string)($step1['first_name'] ?? '')) ?>">
        <input type="hidden" name="last_name"  value="<?= escape((string)($step1['last_name'] ?? '')) ?>">
        <input type="hidden" name="email"      value="<?= escape((string)($step1['email'] ?? '')) ?>">

        <div class="grid">
          <div>
            <label for="username">Username <span class="req">*</span></label>
            <input id="username" name="username" type="text" placeholder="e.g. janedoe" value="<?= oldValue($old2,'username') ?>">
          </div>

          <div>
            <label for="password">Password <span class="req">*</span></label>
            <input id="password" name="password" type="password" placeholder="Minimum 6 characters" value="">
          </div>

          <div class="span-2">
            <label for="plan">Plan <span class="req">*</span></label>
            <select id="plan" name="plan">
              <option value="">Select a plan</option>
              <option value="basic"   <?= oldValue($old2,'plan') === 'basic' ? 'selected' : '' ?>>Basic</option>
              <option value="pro"     <?= oldValue($old2,'plan') === 'pro' ? 'selected' : '' ?>>Pro</option>
              <option value="premium" <?= oldValue($old2,'plan') === 'premium' ? 'selected' : '' ?>>Premium</option>
            </select>
          </div>

          <div class="span-2">
            <div class="row" role="group" aria-label="Terms">
              <label>
                <input type="checkbox" name="terms" value="yes" <?= isChecked($old2,'terms','yes') ?>>
                I accept the terms and conditions. <span class="req">*</span>
              </label>
            </div>
          </div>
        </div>

        <div class="actions">
          <a class="link" href="step1.php">Back to Step 1</a>
          <button class="btn" type="submit">Finish Registration</button>
        </div>

        <div class="footer">© <?= (int)date('Y') ?> Wizard • Hidden inputs + session</div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
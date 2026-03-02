<?php
// contact_self.php - Single-file PRG (Post/Redirect/Get) + PHPMD-friendly (no superglobals inside helpers)

declare(strict_types=1);
session_start();

/* -----------------------------
   Helpers (NO superglobals)
------------------------------ */
function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function flashSet(array &$session, string $key, mixed $value): void {
    if (!isset($session['_flash']) || !is_array($session['_flash'])) {
        $session['_flash'] = [];
    }
    $session['_flash'][$key] = $value;
}

function flashGet(array &$session, string $key, mixed $default = null): mixed {
    if (!isset($session['_flash']) || !is_array($session['_flash']) || !array_key_exists($key, $session['_flash'])) {
        return $default;
    }
    $val = $session['_flash'][$key];
    unset($session['_flash'][$key]);
    return $val;
}

function oldValue(array $old, string $name, string $default = ''): string {
    if (!array_key_exists($name, $old)) return $default;
    $val = $old[$name];
    if (is_array($val)) return $default;
    return escape((string)$val);
}

function isChecked(array $old, string $name, string $value): string {
    if (!array_key_exists($name, $old)) return '';
    $val = $old[$name];
    if (is_array($val)) return in_array($value, $val, true) ? 'checked' : '';
    return ((string)$val === $value) ? 'checked' : '';
}

function isSelected(array $old, string $name, string $value): string {
    if (!array_key_exists($name, $old)) return '';
    return ((string)$old[$name] === $value) ? 'selected' : '';
}

/* -----------------------------
   POST handler (PRG)
------------------------------ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post = $_POST;                 // single access point
    $session =& $_SESSION;          // single access point

    $name    = trim((string)($post['name'] ?? ''));
    $email   = trim((string)($post['email'] ?? ''));
    $subject = trim((string)($post['subject'] ?? ''));
    $topic   = (string)($post['topic'] ?? '');
    $message = trim((string)($post['message'] ?? ''));
    $consent = (string)($post['consent'] ?? '');

    $errors = [];

    if ($name === '') $errors[] = 'Name is required.';
    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email must be a valid email address.';
    }

    if ($subject === '') $errors[] = 'Subject is required.';
    if ($topic === '') $errors[] = 'Please select a topic.';
    if ($message === '') $errors[] = 'Message is required.';
    if ($consent !== 'yes') $errors[] = 'You must agree to be contacted back.';

    $old = [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'topic' => $topic,
        'message' => $message,
        'consent' => $consent,
    ];

    if (!empty($errors)) {
        flashSet($session, 'errors', $errors);
        flashSet($session, 'old', $old);
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    flashSet($session, 'success', 'Thanks! Your message has been sent.');
    flashSet($session, 'submitted', [
        'Name' => $name,
        'Email' => $email,
        'Subject' => $subject,
        'Topic' => $topic,
        'Message' => $message,
    ]);

    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

/* -----------------------------
   GET handler (render)
------------------------------ */
$session =& $_SESSION; // single access point
$errors = flashGet($session, 'errors', []);
$old    = flashGet($session, 'old', []);
$success = (string)flashGet($session, 'success', '');
$submitted = flashGet($session, 'submitted', null);

if (!is_array($errors)) $errors = [];
if (!is_array($old)) $old = [];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Contact Form (PRG)</title>
<style>
:root{
  --bg:#f6f7fb;
  --card:#fff;
  --text:#111827;
  --muted:#6b7280;
  --border:#e5e7eb;
  --primary:#2563eb;
  --primaryHover:#1e40af;
  --dangerBg:#fef2f2;
  --dangerBorder:#fecaca;
  --dangerText:#b91c1c;
  --okBg:#ecfdf5;
  --okBorder:#bbf7d0;
  --okText:#047857;
  --shadow:0 12px 30px rgba(0,0,0,.06);
  --radius:14px;
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
.header{margin-bottom:18px;text-align:center}
.header h1{margin:0 0 6px;font-size:28px;letter-spacing:.2px}
.header p{margin:0;color:var(--muted);line-height:1.5}

.card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  overflow:hidden;
}
.card-body{padding:22px}

.alert{
  border-radius:12px;
  padding:12px 14px;
  border:1px solid transparent;
  margin:0 0 16px;
  font-size:14px;
}
.alert strong{display:block;margin-bottom:6px}
.alert ul{margin:6px 0 0 18px}
.alert-danger{background:var(--dangerBg);border-color:var(--dangerBorder);color:var(--dangerText)}
.alert-ok{background:var(--okBg);border-color:var(--okBorder);color:var(--okText)}

.grid{display:grid;gap:14px}
@media (min-width:760px){
  .grid{grid-template-columns:1fr 1fr}
  .span-2{grid-column:1 / -1}
}

label{display:block;font-size:13px;font-weight:650;margin:0 0 6px}
.req{color:var(--primary);font-weight:700}
.help{margin:6px 0 0;color:var(--muted);font-size:12px;line-height:1.4}

input,select,textarea{
  width:100%;
  padding:10px 12px;
  border-radius:10px;
  border:1px solid var(--border);
  background:#fff;
  color:var(--text);
  outline:none;
  transition:.15s ease;
  font-size:14px;
}
textarea{min-height:120px;resize:vertical}
input:focus,select:focus,textarea:focus{
  border-color:rgba(37,99,235,.65);
  box-shadow:0 0 0 4px rgba(37,99,235,.12);
}

.row{
  display:flex; gap:14px; flex-wrap:wrap; align-items:center;
  padding:10px 12px;
  border:1px solid var(--border);
  border-radius:10px;
  background:#fafafa;
}
.row label{margin:0;font-weight:600;font-size:14px}
.row input{width:auto}

.actions{
  margin-top:16px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  padding-top:16px;
  border-top:1px solid var(--border);
}
.btn{
  border:none;
  border-radius:12px;
  background:var(--primary);
  color:#fff;
  font-weight:700;
  padding:10px 16px;
  cursor:pointer;
  transition:.15s ease;
  min-width:140px;
}
.btn:hover{background:var(--primaryHover)}
.note{color:var(--muted);font-size:12px;margin:0}

.kv{
  display:grid;
  grid-template-columns:140px 1fr;
  gap:10px 14px;
  padding:12px;
  border:1px solid var(--border);
  border-radius:12px;
  background:#fafafa;
  margin-top:10px;
}
.k{color:var(--muted);font-size:12px}
.v{white-space:pre-wrap;font-size:14px}
.footer{margin-top:14px;text-align:center;color:var(--muted);font-size:12px}
</style>
</head>

<body>
<div class="wrap">
  <div class="header">
    <h1>Contact Us</h1>
    <p>Single-file contact form using the Post/Redirect/Get pattern to prevent duplicate submissions.</p>
  </div>

  <div class="card">
    <div class="card-body">

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert" aria-live="polite">
          <strong>Please fix the following:</strong>
          <ul>
            <?php foreach ($errors as $msg): ?>
              <li><?= escape((string)$msg) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($success !== ''): ?>
        <div class="alert alert-ok" role="status" aria-live="polite">
          <strong><?= escape($success) ?></strong>
          <?php if (is_array($submitted)): ?>
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
        <div class="grid">
          <div>
            <label for="name">Name <span class="req">*</span></label>
            <input id="name" name="name" type="text" placeholder="e.g. Jane Doe" value="<?= oldValue($old, 'name') ?>">
          </div>

          <div>
            <label for="email">Email <span class="req">*</span></label>
            <input id="email" name="email" type="email" placeholder="name@example.com" value="<?= oldValue($old, 'email') ?>">
          </div>

          <div>
            <label for="subject">Subject <span class="req">*</span></label>
            <input id="subject" name="subject" type="text" placeholder="How can we help?" value="<?= oldValue($old, 'subject') ?>">
          </div>

          <div>
            <label for="topic">Topic <span class="req">*</span></label>
            <select id="topic" name="topic">
              <option value="">Select a topic</option>
              <option value="support" <?= isSelected($old, 'topic', 'support') ?>>Support</option>
              <option value="billing" <?= isSelected($old, 'topic', 'billing') ?>>Billing</option>
              <option value="feedback" <?= isSelected($old, 'topic', 'feedback') ?>>Feedback</option>
            </select>
            <p class="help">Choose the category that best matches your request.</p>
          </div>

          <div class="span-2">
            <label for="message">Message <span class="req">*</span></label>
            <textarea id="message" name="message" placeholder="Write your message..."><?= oldValue($old, 'message') ?></textarea>
          </div>

          <div class="span-2">
            <div class="row" role="group" aria-label="Consent">
              <label>
                <input type="checkbox" name="consent" value="yes" <?= isChecked($old, 'consent', 'yes') ?>>
                I agree to be contacted back regarding this request. <span class="req">*</span>
              </label>
            </div>
          </div>
        </div>

        <div class="actions">
          <p class="note"><span class="req">*</span> Required fields</p>
          <button class="btn" type="submit">Send Message</button>
        </div>

        <div class="footer">© <?= (int)date('Y') ?> Contact Form • PRG Pattern</div>
      </form>

    </div>
  </div>
</div>
</body>
</html>
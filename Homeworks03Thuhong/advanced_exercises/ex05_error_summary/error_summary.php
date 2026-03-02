<?php
// error_summary.php
// Simple example: Centralized validation summary + field highlighting (PHP only)

declare(strict_types=1);

function e(string $value): string
{
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$errors = [];          // Collect ALL errors here
$fieldErrors = [];     // Optional: per-field errors for inline messages
$values = [
  'full_name' => '',
  'email'     => '',
  'age'       => '',
  'website'   => '',
  'message'   => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Pull & trim inputs
  foreach ($values as $k => $_) {
    $values[$k] = trim((string)($_POST[$k] ?? ''));
  }

  // Validate: Full name
  if ($values['full_name'] === '') {
    $errors[] = 'Full name is required.';
    $fieldErrors['full_name'] = 'Please enter your full name.';
  } elseif (mb_strlen($values['full_name']) < 2) {
    $errors[] = 'Full name must be at least 2 characters.';
    $fieldErrors['full_name'] = 'Too short.';
  }

  // Validate: Email
  if ($values['email'] === '') {
    $errors[] = 'Email is required.';
    $fieldErrors['email'] = 'Please enter your email address.';
  } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email must be a valid email address.';
    $fieldErrors['email'] = 'Invalid email format.';
  }

  // Validate: Age (optional, but if provided must be 13-120)
  if ($values['age'] !== '') {
    if (!ctype_digit($values['age'])) {
      $errors[] = 'Age must be a whole number.';
      $fieldErrors['age'] = 'Use digits only.';
    } else {
      $age = (int)$values['age'];
      if ($age < 13 || $age > 120) {
        $errors[] = 'Age must be between 13 and 120.';
        $fieldErrors['age'] = 'Out of range.';
      }
    }
  }

  // Validate: Website (optional)
  if ($values['website'] !== '') {
    // Accepts http(s) URLs; add scheme if user omitted
    $website = $values['website'];
    if (!preg_match('~^https?://~i', $website)) {
      $website = 'https://' . $website;
    }
    if (!filter_var($website, FILTER_VALIDATE_URL)) {
      $errors[] = 'Website must be a valid URL.';
      $fieldErrors['website'] = 'Invalid URL.';
    } else {
      $values['website'] = $website; // normalize
    }
  }

  // Validate: Message
  if ($values['message'] === '') {
    $errors[] = 'Message is required.';
    $fieldErrors['message'] = 'Please add a short message.';
  } elseif (mb_strlen($values['message']) < 10) {
    $errors[] = 'Message must be at least 10 characters.';
    $fieldErrors['message'] = 'Please write a bit more.';
  }

  // If no errors, you can proceed (save to DB, email, etc.)
  if (empty($errors)) {
    // Example "success" state
    $success = true;
  }
}

// Helper: mark invalid fields
function invalidClass(array $fieldErrors, string $name): string
{
  return isset($fieldErrors[$name]) ? ' is-invalid' : '';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Error Summary – Form Validation</title>
  <style>
    :root{
      --bg:#0b1220; --card:#0f1a30; --text:#e7edf8; --muted:#a7b3cc;
      --border:rgba(255,255,255,.10); --focus:rgba(99,179,237,.45);
      --danger:#ff5a6a; --dangerBg:rgba(255,90,106,.12);
      --ok:#2be4a7; --okBg:rgba(43,228,167,.12);
      --btn:#2b6cff; --btnHover:#1f57d6;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      background: radial-gradient(1200px 600px at 10% 0%, rgba(43,108,255,.25), transparent 55%),
                  radial-gradient(900px 500px at 90% 20%, rgba(43,228,167,.18), transparent 60%),
                  var(--bg);
      color:var(--text);
    }
    .wrap{max-width:980px; margin:0 auto; padding:32px 16px 60px}
    .header{display:flex; align-items:flex-end; justify-content:space-between; gap:16px; margin-bottom:18px}
    .title{margin:0; font-size:28px; letter-spacing:.2px}
    .subtitle{margin:6px 0 0; color:var(--muted); font-size:14px}
    .card{
      background: linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02));
      border:1px solid var(--border);
      border-radius:16px;
      padding:18px;
      box-shadow: 0 10px 30px rgba(0,0,0,.25);
    }
    .alert{
      border-radius:14px;
      padding:14px 14px 12px;
      border:1px solid var(--border);
      margin:0 0 16px;
    }
    .alert-danger{border-color:rgba(255,90,106,.35); background:var(--dangerBg)}
    .alert-success{border-color:rgba(43,228,167,.35); background:var(--okBg)}
    .alert h3{margin:0 0 8px; font-size:15px}
    .alert ul{margin:0; padding-left:18px; color:var(--text)}
    .grid{display:grid; grid-template-columns: 1fr 1fr; gap:14px}
    @media (max-width: 720px){ .grid{grid-template-columns: 1fr} }
    label{display:block; font-size:13px; color:var(--muted); margin:0 0 6px}
    input, textarea{
      width:100%;
      padding:11px 12px;
      border-radius:12px;
      border:1px solid var(--border);
      background: rgba(255,255,255,.04);
      color:var(--text);
      outline:none;
      transition: box-shadow .15s ease, border-color .15s ease;
    }
    textarea{min-height:110px; resize:vertical}
    input:focus, textarea:focus{ box-shadow:0 0 0 4px var(--focus); border-color: rgba(99,179,237,.55) }
    .is-invalid{ border-color: rgba(255,90,106,.55) !important; box-shadow:0 0 0 4px rgba(255,90,106,.18) }
    .hint{margin-top:6px; font-size:12px; color:rgba(255,255,255,.75)}
    .field-error{margin-top:6px; font-size:12px; color: rgba(255,90,106,.95)}
    .actions{display:flex; gap:10px; margin-top:14px; align-items:center}
    .btn{
      display:inline-flex; align-items:center; justify-content:center;
      padding:11px 14px;
      border-radius:12px;
      border:1px solid rgba(43,108,255,.35);
      background: linear-gradient(180deg, rgba(43,108,255,.95), rgba(43,108,255,.75));
      color:white; cursor:pointer; font-weight:600;
    }
    .btn:hover{ background: linear-gradient(180deg, rgba(31,87,214,.98), rgba(31,87,214,.78)) }
    .btn-secondary{
      background: rgba(255,255,255,.06);
      border:1px solid var(--border);
      color:var(--text);
      text-decoration:none;
    }
    .btn-secondary:hover{ background: rgba(255,255,255,.09) }
    .footer-note{margin-top:10px; color:var(--muted); font-size:12px}
    .required{color:rgba(255,255,255,.85)}
    .req-dot{color:var(--danger); font-weight:700}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="header">
      <div>
        <h1 class="title">Error Summary Block</h1>
        <p class="subtitle">All validation feedback is centralized at the top, and invalid fields are highlighted.</p>
      </div>
    </div>

    <div class="card">
      <?php if (!empty($success ?? false)): ?>
        <div class="alert alert-success" role="status" aria-live="polite">
          <h3>✅ Success</h3>
          <div class="hint">Your form was submitted successfully.</div>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <!-- Comprehensive error summary at the top -->
        <div class="alert alert-danger" role="alert" aria-live="assertive">
          <h3>⚠ Please fix the following <?= count($errors) ?> issue(s):</h3>
          <ul>
            <?php foreach ($errors as $msg): ?>
              <li><?= e($msg) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= e($_SERVER['PHP_SELF']) ?>" novalidate>
        <div class="grid">
          <div>
            <label for="full_name" class="required">Full name <span class="req-dot">*</span></label>
            <input
              id="full_name"
              name="full_name"
              type="text"
              value="<?= e($values['full_name']) ?>"
              class="<?= invalidClass($fieldErrors, 'full_name') ?>"
              autocomplete="name"
            />
            <?php if (isset($fieldErrors['full_name'])): ?>
              <div class="field-error"><?= e($fieldErrors['full_name']) ?></div>
            <?php else: ?>
              <div class="hint">Enter your legal or preferred name.</div>
            <?php endif; ?>
          </div>

          <div>
            <label for="email" class="required">Email <span class="req-dot">*</span></label>
            <input
              id="email"
              name="email"
              type="email"
              value="<?= e($values['email']) ?>"
              class="<?= invalidClass($fieldErrors, 'email') ?>"
              autocomplete="email"
            />
            <?php if (isset($fieldErrors['email'])): ?>
              <div class="field-error"><?= e($fieldErrors['email']) ?></div>
            <?php else: ?>
              <div class="hint">We’ll only use this to contact you back.</div>
            <?php endif; ?>
          </div>

          <div>
            <label for="age">Age</label>
            <input
              id="age"
              name="age"
              type="text"
              inputmode="numeric"
              value="<?= e($values['age']) ?>"
              class="<?= invalidClass($fieldErrors, 'age') ?>"
            />
            <?php if (isset($fieldErrors['age'])): ?>
              <div class="field-error"><?= e($fieldErrors['age']) ?></div>
            <?php else: ?>
              <div class="hint">Optional. If provided, must be 13–120.</div>
            <?php endif; ?>
          </div>

          <div>
            <label for="website">Website</label>
            <input
              id="website"
              name="website"
              type="text"
              value="<?= e($values['website']) ?>"
              class="<?= invalidClass($fieldErrors, 'website') ?>"
              placeholder="example.com"
            />
            <?php if (isset($fieldErrors['website'])): ?>
              <div class="field-error"><?= e($fieldErrors['website']) ?></div>
            <?php else: ?>
              <div class="hint">Optional. Add http(s):// if you want.</div>
            <?php endif; ?>
          </div>
        </div>

        <div style="margin-top:14px">
          <label for="message" class="required">Message <span class="req-dot">*</span></label>
          <textarea
            id="message"
            name="message"
            class="<?= invalidClass($fieldErrors, 'message') ?>"
          ><?= e($values['message']) ?></textarea>
          <?php if (isset($fieldErrors['message'])): ?>
            <div class="field-error"><?= e($fieldErrors['message']) ?></div>
          <?php else: ?>
            <div class="hint">At least 10 characters.</div>
          <?php endif; ?>
        </div>

        <div class="actions">
          <button class="btn" type="submit">Submit</button>
          <a class="btn btn-secondary" href="<?= e($_SERVER['PHP_SELF']) ?>">Reset</a>
        </div>

        <div class="footer-note">
          Tip: The summary at the top lists every validation issue, while each invalid field is visually highlighted.
        </div>
      </form>
    </div>
  </div>
</body>
</html>
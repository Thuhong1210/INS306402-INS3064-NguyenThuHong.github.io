<?php
declare(strict_types=1);

function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$firstRaw = $_POST['first'] ?? '';
$secondRaw = $_POST['second'] ?? '';
$operation = $_POST['operation'] ?? 'add';
$errors = [];
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($firstRaw === '') {
        $errors[] = 'First number is required.';
    } elseif (!is_numeric($firstRaw)) {
        $errors[] = 'First value must be a number.';
    }

    if ($secondRaw === '') {
        $errors[] = 'Second number is required.';
    } elseif (!is_numeric($secondRaw)) {
        $errors[] = 'Second value must be a number.';
    }

    if (empty($errors)) {
        $firstNumber = (float)$firstRaw;
        $secondNumber = (float)$secondRaw;

        switch ($operation) {
            case 'add':
                $result = $firstNumber + $secondNumber;
                break;
            case 'subtract':
                $result = $firstNumber - $secondNumber;
                break;
            case 'multiply':
                $result = $firstNumber * $secondNumber;
                break;
            case 'divide':
                if ($secondNumber == 0.0) {
                    $errors[] = 'Division by zero is not allowed.';
                } else {
                    $result = $firstNumber / $secondNumber;
                }
                break;
            case 'modulo':
                if ($secondNumber == 0.0) {
                    $errors[] = 'Modulo by zero is not allowed.';
                } else {
                    $result = fmod($firstNumber, $secondNumber);
                }
                break;
            case 'power':
                $result = pow($firstNumber, $secondNumber);
                break;
            default:
                $errors[] = 'Invalid operation selected.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Arithmetic Calculator</title>
<style>
    :root{--bg:#f4f7fb;--card:#fff;--accent:#0b75ef;--muted:#6b7280}
    body{margin:0;font-family:Inter,Roboto,Segoe UI,Arial;background:var(--bg);display:flex;align-items:center;justify-content:center;height:100vh}
    .card{background:var(--card);padding:24px;border-radius:12px;box-shadow:0 6px 18px rgba(15,23,42,0.08);width:360px}
    h1{margin:0 0 12px;font-size:18px;color:#0f172a}
    label{display:block;font-size:13px;color:var(--muted);margin-top:10px}
    input[type="number"], select{width:100%;padding:10px;border:1px solid #e6e9ef;border-radius:8px;margin-top:6px;font-size:15px}
    .row{display:flex;gap:10px}
    .row > *{flex:1}
    button{margin-top:14px;width:100%;padding:10px;border:0;border-radius:8px;background:var(--accent);color:#fff;font-weight:600;cursor:pointer}
    .result{margin-top:14px;padding:12px;border-radius:8px;background:#f1f5f9;color:#0f172a;font-weight:600}
    .errors{margin-top:12px;color:#b91c1c;font-size:14px}
    .muted{font-size:13px;color:var(--muted);margin-top:6px}
</style>
</head>
<body>
  <main class="card" role="main">
    <h1>Arithmetic Calculator</h1>
    <form method="post" novalidate>
      <label for="first">First number</label>
      <input id="first" name="first" type="number" step="any" value="<?= escape($firstRaw) ?>" required>

      <label for="second">Second number</label>
      <input id="second" name="second" type="number" step="any" value="<?= escape($secondRaw) ?>" required>

      <label for="operation">Operation</label>
      <select id="operation" name="operation">
        <option value="add" <?= $operation === 'add' ? 'selected' : '' ?>>Add (+)</option>
        <option value="subtract" <?= $operation === 'subtract' ? 'selected' : '' ?>>Subtract (−)</option>
        <option value="multiply" <?= $operation === 'multiply' ? 'selected' : '' ?>>Multiply (×)</option>
        <option value="divide" <?= $operation === 'divide' ? 'selected' : '' ?>>Divide (÷)</option>
        <option value="modulo" <?= $operation === 'modulo' ? 'selected' : '' ?>>Modulo (%)</option>
        <option value="power" <?= $operation === 'power' ? 'selected' : '' ?>>Power (^)</option>
      </select>

      <button type="submit">Calculate</button>
    </form>

    <?php if (!empty($errors)): ?>
      <div class="errors" role="alert">
        <?php foreach ($errors as $error): ?>
          <div><?= escape($error) ?></div>
        <?php endforeach; ?>
      </div>
    <?php elseif ($result !== null): ?>
      <div class="result" aria-live="polite">Result: <?= escape((string)$result) ?></div>
      <div class="muted">Computed with type-checked inputs.</div>
    <?php endif; ?>
  </main>
</body>
</html>
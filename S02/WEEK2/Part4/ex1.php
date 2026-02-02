<?php
// /Applications/XAMPP/xamppfiles/htdocs/s02/WEEK2/Part4/ex1.php

function calculateBMI($kg, $m) {
    $kg = floatval($kg);
    $m = floatval($m);
    if ($m <= 0) {
        return null;
    }
    $bmi = $kg / ($m * $m);
    $bmiRounded = round($bmi, 1);

    if ($bmi < 18.5) {
        $category = 'Under';
    } elseif ($bmi <= 24.9) {
        $category = 'Normal';
    } else {
        $category = 'Over';
    }

    return [
        'bmi' => $bmiRounded,
        'category' => $category,
        'text' => "BMI: {$bmiRounded} ({$category})"
    ];
}

$result = null;
$error = '';
$kg = isset($_POST['kg']) ? $_POST['kg'] : '';
$m = isset($_POST['m']) ? $_POST['m'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($kg === '' || $m === '') {
        $error = 'Please provide weight (kg) and height (m).';
    } elseif (!is_numeric($kg) || !is_numeric($m) || floatval($kg) <= 0 || floatval($m) <= 0) {
        $error = 'Enter valid positive numbers for weight and height.';
    } else {
        $result = calculateBMI($kg, $m);
        if ($result === null) {
            $error = 'Height must be greater than zero.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>BMI Calculator</title>
<style>
    :root{--bg:#0f1724;--card:#0b1220;--accent:#06b6d4;--muted:#9aa4b2}
    *{box-sizing:border-box;font-family:Inter,ui-sans-serif,system-ui,Segoe UI,Roboto,Helvetica,Arial}
    body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:
        radial-gradient(1200px 600px at 10% 10%, rgba(6,182,212,0.06), transparent 10%),
        linear-gradient(180deg,#071021 0%,#071021 40%,#071528 100%);color:#e6eef6}
    .card{width:100%;max-width:540px;background:linear-gradient(180deg,rgba(255,255,255,0.02),rgba(255,255,255,0.01));
        border:1px solid rgba(255,255,255,0.04);padding:28px;border-radius:12px;box-shadow:0 6px 30px rgba(2,6,23,0.6)}
    h1{margin:0 0 8px;font-size:20px;letter-spacing:-0.2px}
    p.lead{margin:0 0 18px;color:var(--muted);font-size:14px}
    form{display:grid;grid-template-columns:1fr 1fr;gap:12px 14px;margin-bottom:18px}
    label{display:block;font-size:13px;color:var(--muted);margin-bottom:6px}
    input[type="number"]{width:100%;padding:10px 12px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);
        background:transparent;color:inherit;font-size:15px}
    .full{grid-column:1 / -1}
    button{appearance:none;border:0;padding:10px 14px;border-radius:10px;background:linear-gradient(90deg,var(--accent),#2dd4bf);
        color:#042029;font-weight:600;cursor:pointer;font-size:15px}
    .result{padding:14px;border-radius:10px;background:linear-gradient(180deg,rgba(255,255,255,0.02),transparent);
        border:1px solid rgba(255,255,255,0.03);display:flex;align-items:center;gap:12px}
    .badge{font-weight:700;padding:8px 12px;border-radius:999px;background:rgba(255,255,255,0.03);font-size:14px}
    .error{color:#ffc1c1;background:rgba(255,192,192,0.04);padding:10px;border-radius:8px;font-size:14px}
    footer{margin-top:14px;color:var(--muted);font-size:13px;text-align:right}
    @media (max-width:520px){form{grid-template-columns:1fr} .full{grid-column:auto}}
</style>
</head>
<body>
<main class="card" role="main">
    <h1>BMI Calculator</h1>
    <p class="lead">Calculate your Body Mass Index and see the category (Under / Normal / Over).</p>

    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div>
            <label for="kg">Weight (kg)</label>
            <input id="kg" name="kg" type="number" step="0.1" min="0" placeholder="e.g. 70.0" value="<?php echo htmlspecialchars($kg); ?>">
        </div>
        <div>
            <label for="m">Height (m)</label>
            <input id="m" name="m" type="number" step="0.01" min="0" placeholder="e.g. 1.75" value="<?php echo htmlspecialchars($m); ?>">
        </div>

        <div class="full" style="display:flex;gap:10px;align-items:center">
            <button type="submit">Calculate</button>
            <?php if ($result): ?>
                <div class="result" style="margin-left:auto">
                    <div class="badge"><?php echo htmlspecialchars($result['bmi']); ?></div>
                    <div>
                        <div style="font-weight:700">BMI</div>
                        <div style="color:var(--muted)"><?php echo htmlspecialchars("({$result['category']})"); ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($result): ?>
        <div style="margin-top:8px;font-size:15px">
            <?php echo htmlspecialchars($result['text']); ?>
        </div>
    <?php endif; ?>

    <footer>Example: "BMI: 22.1 (Normal)"</footer>
</main>
</body>
</html>
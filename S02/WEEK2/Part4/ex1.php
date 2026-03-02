<?php
function calculateBMI($kg, $m) {
  $kg = (float)$kg;
  $m  = (float)$m;

  if ($kg <= 0 || $m <= 0) {
    return ['bmi' => null, 'category' => 'Invalid'];
  }

  $bmi = $kg / ($m * $m);

  if ($bmi < 18.5) $category = 'Under';
  elseif ($bmi < 25) $category = 'Normal'; // 18.5 - 24.9
  else $category = 'Over';                 // 25+

  return ['bmi' => $bmi, 'category' => $category];
}

$resultText = '';
$resultMeta = ['category' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $kg = $_POST['kg'] ?? '';
  $m  = $_POST['m']  ?? '';

  $res = calculateBMI($kg, $m);
  $resultMeta = $res;

  if ($res['bmi'] === null) {
    $resultText = 'Vui lòng nhập cân nặng và chiều cao hợp lệ.';
  } else {
    $bmi1 = number_format($res['bmi'], 1);
    $resultText = "BMI: {$bmi1} ({$res['category']})"; // example_output format
  }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>BMI Calculator</title>
  <style>
    :root{
      --bg1:#070b14;
      --bg2:#0b1220;
      --card: rgba(255,255,255,.06);
      --stroke: rgba(255,255,255,.14);
      --text: rgba(255,255,255,.92);
      --muted: rgba(255,255,255,.65);
      --shadow: 0 18px 60px rgba(0,0,0,.45);
      --radius: 18px;
    }
    *{ box-sizing:border-box; }
    body{
      margin:0;
      min-height:100vh;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      color: var(--text);
      background:
        radial-gradient(1200px 500px at 18% 0%, #1c4ed8 0%, transparent 55%),
        radial-gradient(900px 500px at 92% 15%, #a21caf 0%, transparent 55%),
        linear-gradient(180deg, var(--bg1) 0%, var(--bg2) 60%, var(--bg1) 100%);
      display:flex;
      align-items:center;
      justify-content:center;
      padding: 28px;
    }
    .wrap{ width:min(980px,100%); display:grid; gap:14px; grid-template-columns: 1.2fr .8fr; }
    @media (max-width: 860px){ .wrap{ grid-template-columns: 1fr; } }
    .card{
      border: 1px solid var(--stroke);
      background: linear-gradient(180deg, var(--card) 0%, rgba(255,255,255,.03) 100%);
      box-shadow: var(--shadow);
      border-radius: var(--radius);
      overflow:hidden;
    }
    .header{ padding:18px 18px 10px; border-bottom: 1px solid rgba(255,255,255,.10); }
    h1{ margin:0; font-size:22px; letter-spacing:.2px; }
    .sub{ margin:6px 0 0; color: var(--muted); font-size: 13px; }

    form{ padding:18px; display:grid; gap:12px; }
    .row{ display:grid; grid-template-columns: 1fr 1fr; gap:12px; }
    @media (max-width: 520px){ .row{ grid-template-columns: 1fr; } }

    label{ display:block; font-size: 13px; color: var(--muted); margin: 0 0 8px; }
    .field{
      border: 1px solid rgba(255,255,255,.14);
      background: rgba(255,255,255,.05);
      border-radius: 14px;
      padding: 12px 12px;
      display:flex;
      align-items:center;
      gap:10px;
    }
    input{
      width:100%;
      border:none;
      outline:none;
      background: transparent;
      color: var(--text);
      font-size: 15px;
    }
    .unit{
      color: var(--muted);
      font-size: 13px;
      padding: 4px 8px;
      border: 1px solid rgba(255,255,255,.12);
      border-radius: 999px;
      background: rgba(0,0,0,.12);
      white-space:nowrap;
    }
    .actions{ display:flex; gap:10px; align-items:center; }
    button{
      border: 1px solid rgba(255,255,255,.18);
      background: rgba(255,255,255,.10);
      color: var(--text);
      padding: 12px 14px;
      border-radius: 14px;
      cursor:pointer;
      font-weight: 650;
      letter-spacing:.2px;
    }
    button:hover{ background: rgba(255,255,255,.14); }
    .hint{ color: var(--muted); font-size: 12px; }

    .panel{ padding:18px; }
    .resultBox{
      border: 1px solid rgba(255,255,255,.14);
      background: rgba(255,255,255,.05);
      border-radius: 16px;
      padding: 14px;
      display:grid;
      gap:10px;
    }
    .resultTitle{ margin:0; color: var(--muted); font-size: 13px; }
    .resultValue{
      margin:0;
      font-size: 22px;
      font-weight: 750;
      letter-spacing:.2px;
    }
    .badge{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding: 6px 10px;
      border-radius: 999px;
      border: 1px solid rgba(255,255,255,.16);
      background: rgba(255,255,255,.06);
      font-weight: 700;
      width: fit-content;
    }
    .Under { border-color: rgba(255, 210, 120, .35); background: rgba(255,210,120,.12); }
    .Normal{ border-color: rgba(80, 255, 190, .35); background: rgba(80,255,190,.12); }
    .Over  { border-color: rgba(255, 120, 120, .35); background: rgba(255,120,120,.12); }
    .Invalid{ border-color: rgba(180,180,180,.25); background: rgba(180,180,180,.10); }

    .ranges{
      margin-top: 12px;
      border-top: 1px solid rgba(255,255,255,.10);
      padding-top: 12px;
      color: var(--muted);
      font-size: 13px;
      line-height: 1.5;
    }
    .ranges code{
      color: rgba(255,255,255,.85);
      background: rgba(0,0,0,.18);
      padding: 2px 6px;
      border-radius: 8px;
      border: 1px solid rgba(255,255,255,.10);
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="header">
        <h1>BMI Calculator</h1>
        <p class="sub">Nhập cân nặng (kg) và chiều cao (m) để tính BMI và phân loại.</p>
      </div>

      <form method="post">
        <div class="row">
          <div>
            <label for="kg">Cân nặng</label>
            <div class="field">
              <input id="kg" name="kg" type="number" step="0.1" min="0" placeholder="VD: 65"
                     value="<?php echo isset($_POST['kg']) ? htmlspecialchars($_POST['kg']) : ''; ?>">
              <span class="unit">kg</span>
            </div>
          </div>
          <div>
            <label for="m">Chiều cao</label>
            <div class="field">
              <input id="m" name="m" type="number" step="0.01" min="0" placeholder="VD: 1.70"
                     value

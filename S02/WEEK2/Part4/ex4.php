<?php
// Input: Array of random integers
$scores = [65, 80, 90, 72, 88, 60, 95, 70, 85];

// Calculate: Average, Max, Min
$avg = array_sum($scores) / count($scores);
$max = max($scores);
$min = min($scores);

// Filter: Create new array of scores > Average
$topScores = array_filter($scores, function($s) use ($avg) {
  return $s > $avg;
});

// Format output
$avgRounded = round($avg);
$outputText = "Avg: {$avgRounded}, Top: [" . implode(", ", $topScores) . "]";
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Score Stats</title>

  <style>
    :root{
      --bg:#070b14;
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
      font-family: ui-sans-serif, system-ui, Segoe UI, Roboto, Arial;
      background:
        radial-gradient(1200px 500px at 15% 0%, #2563eb 0%, transparent 55%),
        radial-gradient(900px 500px at 90% 15%, #a21caf 0%, transparent 55%),
        linear-gradient(180deg, #070b14 0%, #0b1220 60%, #070b14 100%);
      display:flex;
      justify-content:center;
      align-items:center;
      padding: 28px;
      color: var(--text);
    }

    .card{
      width: min(900px, 100%);
      border: 1px solid var(--stroke);
      border-radius: var(--radius);
      background: linear-gradient(180deg, var(--card), rgba(255,255,255,.03));
      box-shadow: var(--shadow);
      overflow:hidden;
    }

    .header{
      padding:18px;
      border-bottom:1px solid rgba(255,255,255,.10);
    }

    h1{
      margin:0;
      font-size:22px;
    }

    .sub{
      margin-top:6px;
      color: var(--muted);
      font-size:13px;
    }

    .content{
      padding:18px;
      display:grid;
      gap:14px;
    }

    .stats{
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap:12px;
    }

    .box{
      padding:14px;
      border-radius:16px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.05);
    }

    .label{
      font-size:13px;
      color: var(--muted);
    }

    .value{
      font-size:20px;
      font-weight:750;
      margin-top:6px;
    }

    .topList{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
    }

    .pill{
      padding:8px 12px;
      border-radius:999px;
      border:1px solid rgba(80,255,190,.35);
      background: rgba(80,255,190,.12);
      font-weight:700;
      min-width:60px;
      text-align:center;
    }

    .footer{
      padding:14px 18px;
      border-top:1px solid rgba(255,255,255,.10);
      background: rgba(255,255,255,.03);
      font-size:13px;
      color: var(--muted);
    }

    code{
      color: rgba(255,255,255,.85);
      background: rgba(0,0,0,.25);
      padding:2px 6px;
      border-radius:8px;
      border:1px solid rgba(255,255,255,.12);
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="header">
      <h1>Score Statistics</h1>
      <p class="sub">
        Tính Average, Max, Min và lọc các điểm cao hơn trung bình.
      </p>
    </div>

    <div class="content">
      <!-- Stats -->
      <div class="stats">
        <div class="box">
          <div class="label">Average</div>
          <div class="value"><?php echo round($avg,1); ?></div>
        </div>

        <div class="box">
          <div class="label

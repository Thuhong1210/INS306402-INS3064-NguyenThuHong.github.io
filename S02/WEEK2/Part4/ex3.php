<?php
// Utility function: check prime number
function isPrime(int $n): bool {
  if ($n < 2) return false;
  if ($n === 2) return true;
  if ($n % 2 === 0) return false;

  for ($i = 3; $i * $i <= $n; $i += 2) {
    if ($n % $i === 0) return false;
  }
  return true;
}

// Collect prime numbers from 1 to 100
$primes = [];

for ($i = 1; $i <= 100; $i++) {
  if (isPrime($i)) {
    $primes[] = $i;
  }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Prime Numbers 1–100</title>

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
        radial-gradient(1000px 500px at 15% 0%, #2563eb 0%, transparent 55%),
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
    }

    .grid{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
    }

    .prime{
      padding:8px 12px;
      border-radius:999px;
      border:1px solid rgba(80,255,190,.35);
      background: rgba(80,255,190,.12);
      font-weight:700;
      font-size:14px;
      min-width:50px;
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
      <h1>Prime Numbers (1 → 100)</h1>
      <p class="sub">
        In ra tất cả số nguyên tố từ 1 đến 100 bằng PHP với hàm <code>isPrime()</code>.
      </p>
    </div>

    <div class="content">
      <div class="grid">
        <?php foreach ($primes as $p): ?>
          <div class="prime"><?php echo $p; ?></div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="footer">
      Output dạng text: <code><?php echo implode(", ", $primes); ?></code>
    </div>
  </div>
</body>
</html>

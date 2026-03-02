<?php
require __DIR__ . '/_layout.php';
layoutHead('404 – Page Not Found', $currentPage ?? '');

// $requestedPage is passed by the controller
$requested = isset($requestedPage) ? (string)$requestedPage : '???';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>404 – Page Not Found · SimpleRouter</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --bg: #0d1117; --surface: #161b22; --surface2: #21262d;
  --border: #30363d; --txt: #e6edf3; --txt-muted: #8b949e;
  --primary: #58a6ff; --primary-dim: #1f6feb;
  --accent: #bc8cff; --red: #f85149;
  --green: #3fb950; --orange: #d29922;
  --radius: 12px; --transition: .18s ease;
}
body {
  font-family: 'Inter', system-ui, sans-serif;
  background: var(--bg); color: var(--txt);
  min-height: 100vh;
  display: flex; flex-direction: column;
}
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-thumb { background: var(--border); border-radius: 9px; }

.topbar {
  position: sticky; top: 0; z-index: 100;
  background: rgba(13,17,23,.85); backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
  padding: 0 24px; height: 60px;
  display: flex; align-items: center; justify-content: space-between;
}
.brand {
  display: flex; align-items: center; gap: 10px;
  text-decoration: none; color: var(--txt);
  font-weight: 700; font-size: 15px;
}
.brand-icon {
  width: 32px; height: 32px; border-radius: 8px;
  background: linear-gradient(135deg, var(--primary-dim), var(--accent));
  display: flex; align-items: center; justify-content: center; font-size: 16px;
}
nav { display: flex; gap: 4px; }
nav a {
  text-decoration: none; color: var(--txt-muted);
  font-size: 13.5px; font-weight: 500;
  padding: 6px 12px; border-radius: 8px;
  transition: color var(--transition), background var(--transition);
}
nav a:hover { color: var(--txt); background: var(--surface2); }

main {
  flex: 1; display: flex; align-items: center; justify-content: center;
  padding: 40px 24px; text-align: center;
}
.error-wrap { max-width: 560px; }

.big-code {
  font-size: clamp(80px, 18vw, 140px);
  font-weight: 800; line-height: 1;
  background: linear-gradient(135deg, var(--red), var(--orange));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 8px;
  animation: pulse 2.5s ease-in-out infinite;
}
@keyframes pulse {
  0%, 100% { opacity: 1; } 50% { opacity: .7; }
}

.error-title {
  font-size: 22px; font-weight: 700; margin-bottom: 10px;
}
.error-sub {
  color: var(--txt-muted); font-size: 14.5px; line-height: 1.6; margin-bottom: 28px;
}
.error-sub code {
  background: var(--surface2); padding: 2px 8px;
  border-radius: 6px; color: var(--red); font-size: 13px;
}

.actions-row {
  display: flex; flex-wrap: wrap; gap: 12px; justify-content: center;
  margin-bottom: 32px;
}
.btn {
  padding: 10px 20px; border-radius: 10px; font-size: 14px;
  font-weight: 600; text-decoration: none; transition: .15s;
}
.btn-primary {
  background: linear-gradient(135deg, var(--primary-dim), var(--primary));
  color: #fff; border: none;
}
.btn-primary:hover { opacity: .85; }
.btn-secondary {
  background: var(--surface); color: var(--txt-muted);
  border: 1px solid var(--border);
}
.btn-secondary:hover { color: var(--txt); border-color: var(--txt-muted); }

.code-block {
  background: var(--surface2); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 16px 20px;
  font-family: 'Courier New', monospace; font-size: 12.5px;
  text-align: left; line-height: 1.7; margin-bottom: 0;
}
.code-block .kw  { color: var(--accent); }
.code-block .fn  { color: var(--primary); }
.code-block .str { color: var(--green); }
.code-block .cmt { color: var(--txt-muted); font-style: italic; }
.code-block .var { color: #ffa657; }
.code-block .red { color: var(--red); }

.section-title {
  font-size: 12px; font-weight: 600; text-transform: uppercase;
  letter-spacing: .6px; color: var(--txt-muted);
  margin-bottom: 12px; text-align: left;
  display: flex; align-items: center; gap: 8px;
}
.section-title::after { content: ''; flex:1; height:1px; background: var(--border); }

.footer {
  border-top: 1px solid var(--border); padding: 16px 24px;
  text-align: center; color: var(--txt-muted); font-size: 12.5px;
}
.footer a { color: var(--primary); text-decoration: none; }
.footer a:hover { text-decoration: underline; }

@keyframes fadeUp {
  from { opacity:0; transform:translateY(14px); }
  to   { opacity:1; transform:translateY(0); }
}
.animate { animation: fadeUp .4s ease both; }
.delay-1 { animation-delay: .08s; }
.delay-2 { animation-delay: .18s; }
</style>
</head>
<body>
<header class="topbar">
  <a href="index.php" class="brand">
    <div class="brand-icon">⚡</div>
    SimpleRouter
  </a>
  <nav>
    <a href="index.php">🏠 Home</a>
    <a href="index.php?page=about">👤 About</a>
    <a href="index.php?page=services">⚙️ Services</a>
    <a href="index.php?page=blog">📝 Blog</a>
    <a href="index.php?page=contact">✉️ Contact</a>
  </nav>
</header>

<main>
  <div class="error-wrap">
    <div class="big-code animate">404</div>
    <h1 class="error-title animate delay-1">Page Not Found</h1>
    <p class="error-sub animate delay-1">
      The Front Controller received <code>?page=<?= htmlspecialchars($requested, ENT_QUOTES, 'UTF-8') ?></code>
      but <strong>&quot;<?= htmlspecialchars($requested, ENT_QUOTES, 'UTF-8') ?>&quot;</strong>
      is not in the allow-list.<br>
      <code>http_response_code(404)</code> was set and this view was loaded.
    </p>

    <div class="actions-row animate delay-1">
      <a href="index.php" class="btn btn-primary">🏠 Go to Home</a>
      <a href="javascript:history.back()" class="btn btn-secondary">← Go Back</a>
    </div>

    <div class="animate delay-2">
      <p class="section-title">Why Did This Happen?</p>
      <div class="code-block">
<span class="cmt">// index.php — dispatch logic</span><br>
<span class="var">$allowedPages</span> = [<span class="str">'home'</span>, <span class="str">'about'</span>, <span class="str">'contact'</span>, <span class="str">'blog'</span>, <span class="str">'services'</span>];<br>
<br>
<span class="kw">if</span> (<span class="fn">in_array</span>(<span class="var">$page</span>, <span class="var">$allowedPages</span>, <span class="kw">true</span>)) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">loadView</span>(<span class="var">$page</span>);<br>
} <span class="kw">else</span> {<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">http_response_code</span>(<span class="red">404</span>);&nbsp;&nbsp;<span class="cmt">// ← triggered now</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">loadView</span>(<span class="str">'404'</span>);&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="cmt">// ← you are here</span><br>
}
      </div>
    </div>
  </div>
</main>

<footer class="footer">
  © <?= (int)date('Y') ?> SimpleRouter · ex06 Front Controller &nbsp;|&nbsp;
  <a href="index.php">Home</a>
</footer>
</body>
</html>

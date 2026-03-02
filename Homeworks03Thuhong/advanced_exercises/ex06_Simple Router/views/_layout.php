<?php
// Shared layout helpers — included by each view
// Usage: call layoutHead($title) then layoutNav($currentPage) then layoutFoot()

function layoutHead(string $title, string $currentPage = ''): void { ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="ex06 Simple Router – Front Controller MVC demo with PHP routing via query parameters.">
<title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> · SimpleRouter</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ── Reset & Tokens ──────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --bg:          #0d1117;
  --surface:     #161b22;
  --surface2:    #21262d;
  --border:      #30363d;
  --txt:         #e6edf3;
  --txt-muted:   #8b949e;
  --primary:     #58a6ff;
  --primary-dim: #1f6feb;
  --accent:      #bc8cff;
  --green:       #3fb950;
  --orange:      #d29922;
  --red:         #f85149;
  --radius:      12px;
  --transition:  .18s ease;
}

body {
  font-family: 'Inter', system-ui, sans-serif;
  background: var(--bg);
  color: var(--txt);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  line-height: 1.6;
}

/* ── Scrollbar ───────────────────────────────────────────────── */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: var(--border); border-radius: 9px; }

/* ── Topbar ──────────────────────────────────────────────────── */
.topbar {
  position: sticky; top: 0; z-index: 100;
  background: rgba(13,17,23,.85);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
  padding: 0 24px;
  display: flex; align-items: center; justify-content: space-between;
  height: 60px;
  gap: 16px;
}

.brand {
  display: flex; align-items: center; gap: 10px;
  text-decoration: none; color: var(--txt);
  font-weight: 700; font-size: 15px; letter-spacing: -.2px;
}
.brand-icon {
  width: 32px; height: 32px; border-radius: 8px;
  background: linear-gradient(135deg, var(--primary-dim), var(--accent));
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; flex-shrink: 0;
}

/* ── Nav ─────────────────────────────────────────────────────── */
nav { display: flex; align-items: center; gap: 4px; }

nav a {
  text-decoration: none; color: var(--txt-muted);
  font-size: 13.5px; font-weight: 500;
  padding: 6px 12px; border-radius: 8px;
  transition: color var(--transition), background var(--transition);
  display: flex; align-items: center; gap: 6px;
}
nav a:hover { color: var(--txt); background: var(--surface2); }
nav a.active {
  color: var(--primary);
  background: rgba(88,166,255,.12);
}

/* URL pill in topbar */
.url-pill {
  font-family: 'Courier New', monospace;
  font-size: 11px;
  background: var(--surface2);
  border: 1px solid var(--border);
  color: var(--txt-muted);
  padding: 4px 10px; border-radius: 20px;
  max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.url-pill span { color: var(--primary); }

/* ── Main ────────────────────────────────────────────────────── */
main { flex: 1; padding: 0 24px 60px; }

.container { max-width: 960px; margin: 0 auto; }

/* ── Page‑hero ───────────────────────────────────────────────── */
.page-hero {
  padding: 56px 0 40px;
  display: flex; align-items: flex-start; gap: 20px;
}
.hero-icon {
  width: 56px; height: 56px; border-radius: 16px;
  display: flex; align-items: center; justify-content: center;
  font-size: 26px; flex-shrink: 0; margin-top: 2px;
}
.hero-icon.blue  { background: rgba(88,166,255,.15); }
.hero-icon.purple{ background: rgba(188,140,255,.15); }
.hero-icon.green { background: rgba(63,185,80,.15);  }
.hero-icon.orange{ background: rgba(210,153,34,.15); }
.hero-icon.red   { background: rgba(248,81,73,.15);  }

.page-hero h1 {
  font-size: clamp(24px, 4vw, 36px);
  font-weight: 800; letter-spacing: -.5px; line-height: 1.2;
  margin-bottom: 10px;
}
.page-hero p {
  color: var(--txt-muted); font-size: 15px; max-width: 560px;
}

/* ── Cards grid ──────────────────────────────────────────────── */
.grid { display: grid; gap: 16px; }
.grid-2 { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
.grid-3 { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }

.card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 20px 22px;
  transition: border-color var(--transition), transform var(--transition), box-shadow var(--transition);
}
.card:hover {
  border-color: var(--primary);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(88,166,255,.08);
}
.card h3 { font-size: 15px; font-weight: 700; margin-bottom: 6px; }
.card p  { color: var(--txt-muted); font-size: 13.5px; line-height: 1.55; }
.card-icon { font-size: 22px; margin-bottom: 12px; }

/* ── Code block ──────────────────────────────────────────────── */
.code-block {
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 18px 20px;
  font-family: 'Courier New', monospace;
  font-size: 13px;
  overflow-x: auto; line-height: 1.7;
}
.code-block .kw  { color: var(--accent); }
.code-block .fn  { color: var(--primary); }
.code-block .str { color: var(--green); }
.code-block .cmt { color: var(--txt-muted); font-style: italic; }
.code-block .var { color: #ffa657; }

/* ── Route table ─────────────────────────────────────────────── */
.route-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
.route-table th, .route-table td {
  padding: 11px 16px; text-align: left;
  border-bottom: 1px solid var(--border);
}
.route-table th {
  background: var(--surface2); color: var(--txt-muted);
  font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: .5px;
}
.route-table tr:last-child td { border-bottom: none; }
.route-table tr:hover td { background: rgba(255,255,255,.02); }
.route-table a { color: var(--primary); text-decoration: none; }
.route-table a:hover { text-decoration: underline; }

/* ── Badges ──────────────────────────────────────────────────── */
.badge {
  display: inline-block; padding: 3px 10px; border-radius: 20px;
  font-size: 11.5px; font-weight: 600; letter-spacing: .3px;
}
.badge-green  { background: rgba(63,185,80,.15); color: var(--green); }
.badge-orange { background: rgba(210,153,34,.15); color: var(--orange); }
.badge-red    { background: rgba(248,81,73,.15); color: var(--red); }
.badge-blue   { background: rgba(88,166,255,.15); color: var(--primary); }
.badge-purple { background: rgba(188,140,255,.15); color: var(--accent); }

/* ── Info box ────────────────────────────────────────────────── */
.info-box {
  border-radius: var(--radius);
  padding: 14px 18px;
  font-size: 13.5px; line-height: 1.6;
  display: flex; gap: 12px; align-items: flex-start;
}
.info-box.info   { background: rgba(88,166,255,.08); border: 1px solid rgba(88,166,255,.25); }
.info-box.warn   { background: rgba(210,153,34,.08); border: 1px solid rgba(210,153,34,.25); color: var(--orange); }
.info-box.success{ background: rgba(63,185,80,.08);  border: 1px solid rgba(63,185,80,.25);  color: var(--green);  }
.info-box .icon  { font-size: 18px; flex-shrink: 0; margin-top: 1px; }

/* ── Divider ─────────────────────────────────────────────────── */
.divider { border: none; border-top: 1px solid var(--border); margin: 32px 0; }

/* ── Section title ───────────────────────────────────────────── */
.section-title {
  font-size: 13px; font-weight: 600;
  text-transform: uppercase; letter-spacing: .6px;
  color: var(--txt-muted); margin-bottom: 16px;
  display: flex; align-items: center; gap: 8px;
}
.section-title::after {
  content: ''; flex: 1; height: 1px; background: var(--border);
}

/* ── Footer ──────────────────────────────────────────────────── */
.footer {
  border-top: 1px solid var(--border);
  padding: 18px 24px;
  text-align: center; color: var(--txt-muted);
  font-size: 12.5px;
}
.footer a { color: var(--primary); text-decoration: none; }
.footer a:hover { text-decoration: underline; }

/* ── Animations ──────────────────────────────────────────────── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(14px); }
  to   { opacity: 1; transform: translateY(0); }
}
.animate { animation: fadeUp .4s ease both; }
.delay-1 { animation-delay: .08s; }
.delay-2 { animation-delay: .16s; }
.delay-3 { animation-delay: .24s; }
.delay-4 { animation-delay: .32s; }

/* ── Responsive ──────────────────────────────────────────────── */
@media (max-width: 600px) {
  .topbar { padding: 0 14px; }
  .url-pill { display: none; }
  nav a span.nav-label { display: none; }
  main { padding: 0 14px 48px; }
}
</style>
</head>
<body>
<?php } // end layoutHead

// ─────────────────────────────────────────────────────────────────────
function layoutNav(string $currentPage): void {
    $navItems = [
        'home'     => ['icon' => '🏠', 'label' => 'Home'],
        'about'    => ['icon' => '👤', 'label' => 'About'],
        'services' => ['icon' => '⚙️',  'label' => 'Services'],
        'blog'     => ['icon' => '📝', 'label' => 'Blog'],
        'contact'  => ['icon' => '✉️',  'label' => 'Contact'],
    ];

    $urlParam = $currentPage !== 'home' ? '?page=' . htmlspecialchars($currentPage, ENT_QUOTES, 'UTF-8') : '';
    $fullUrl  = 'index.php' . $urlParam;
?>
<header class="topbar">
  <a href="index.php" class="brand">
    <div class="brand-icon">⚡</div>
    SimpleRouter
  </a>

  <nav role="navigation" aria-label="Main navigation">
    <?php foreach ($navItems as $slug => $item):
        $isActive = ($currentPage === $slug);
        $href     = $slug === 'home' ? 'index.php' : 'index.php?page=' . $slug;
    ?>
    <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
       class="<?= $isActive ? 'active' : '' ?>"
       <?= $isActive ? 'aria-current="page"' : '' ?>>
      <?= $item['icon'] ?> <span class="nav-label"><?= $item['label'] ?></span>
    </a>
    <?php endforeach; ?>
  </nav>

  <div class="url-pill">
    index.php<?= $urlParam !== '' ? '<span>' . htmlspecialchars($urlParam, ENT_QUOTES, 'UTF-8') . '</span>' : '' ?>
  </div>
</header>
<main role="main">
<div class="container">
<?php } // end layoutNav

// ─────────────────────────────────────────────────────────────────────
function layoutFoot(): void { ?>
</div><!-- /container -->
</main>
<footer class="footer">
  © <?= (int)date('Y') ?> SimpleRouter · ex06 Front Controller &nbsp;|&nbsp;
  MVC Demo · PHP <?= PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION ?> &nbsp;|&nbsp;
  <a href="index.php">Home</a>
</footer>
</body>
</html>
<?php } // end layoutFoot

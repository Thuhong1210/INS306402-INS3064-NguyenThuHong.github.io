<?php
// search.php
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$safe = htmlspecialchars($q, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Search</title>
    <style>
        :root{--bg:#f5f7fb;--card:#fff;--accent:#2b6cff;--muted:#6b7280}
        *{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,-apple-system,Arial;background:var(--bg);color:#111}
        .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:32px}
        .card{width:100%;max-width:720px;background:var(--card);padding:28px;border-radius:12px;box-shadow:0 6px 20px rgba(15,23,42,0.08)}
        h1{margin:0 0 12px;font-size:20px}
        p.lead{margin:0 0 18px;color:var(--muted);font-size:14px}
        form{display:flex;gap:10px}
        input[type="search"]{flex:1;padding:12px 14px;border:1px solid #e6e9ef;border-radius:8px;font-size:15px}
        button{background:var(--accent);color:#fff;border:0;padding:10px 14px;border-radius:8px;font-weight:600;cursor:pointer}
        .meta{margin-top:16px;color:var(--muted);font-size:13px}
        .result{margin-top:12px;padding:14px;border-radius:8px;background:#f8fafc;border:1px solid #eef2ff}
        a.clear{margin-left:8px;color:var(--accent);text-decoration:none;font-size:13px}
        @media (max-width:420px){.card{padding:18px}}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Search</h1>
            <p class="lead">Type a query and press Enter. The input is preserved in the URL and in the field.</p>

            <form method="get" action="">
                <input type="search" name="q" value="<?php echo $safe; ?>" placeholder="Search..." autocomplete="off" autofocus />
                <button type="submit">Search</button>
                <?php if ($q !== ''): ?>
                    <a class="clear" href="<?php echo strtok($_SERVER['REQUEST_URI'], '?'); ?>">Clear</a>
                <?php endif; ?>
            </form>

            <div class="meta">
                <?php if ($q === ''): ?>
                    <div class="result">No query provided. Try searching for "php", "css", or any keyword.</div>
                <?php else: ?>
                    <div class="result">
                        You searched for: <strong><?php echo $safe; ?></strong>
                        <div style="margin-top:8px;color:var(--muted);font-size:13px">
                            GET parameter: <code><?php echo htmlspecialchars($_SERVER['QUERY_STRING'], ENT_QUOTES, 'UTF-8'); ?></code>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
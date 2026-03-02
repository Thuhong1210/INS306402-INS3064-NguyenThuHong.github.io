<?php
declare(strict_types=1);

// method_toggle.php - GET vs POST Toggle (single-file controller + view)

// -------- Helpers (no superglobals inside helpers; PHPMD-friendly names) --------
function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function normalizeArray(mixed $value): array {
    return is_array($value) ? $value : [];
}

function dumpArrayForHtml(array $data): string {
    // Pretty JSON for readability (safe for HTML output)
    $jsonText = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if (!is_string($jsonText)) $jsonText = '{}';
    return escape($jsonText);
}

/* -------- Read superglobals ONCE -------- */
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$getData = normalizeArray($_GET);
$postData = normalizeArray($_POST);
$activeData = ($requestMethod === 'POST') ? $postData : $getData;

/* -------- Form mode toggle --------
   Default: GET
   Users can switch via query (?mode=get|post) OR via POST hidden field
*/
$modeParam = '';
if (isset($getData['mode'])) $modeParam = strtolower((string)$getData['mode']);
if ($requestMethod === 'POST' && isset($postData['mode'])) $modeParam = strtolower((string)$postData['mode']);

$formMode = ($modeParam === 'post') ? 'post' : 'get';

/* If user is currently in POST method but selected GET mode, keep the toggle stable on next render */
$toggleModeForLink = ($formMode === 'post') ? 'get' : 'post';

/* -------- Controller message -------- */
$controllerNote = ($requestMethod === 'POST')
    ? 'Controller detected REQUEST_METHOD = POST. Showing $_POST.'
    : 'Controller detected REQUEST_METHOD = GET. Showing $_GET.';

// Sample fields (keep consistent across both methods)
$fields = [
    'full_name' => 'Full name',
    'email' => 'Email',
    'query' => 'Search query',
];

function oldValue(string $fieldName, array $getData, array $postData, string $requestMethod): string {
    if ($requestMethod === 'POST') {
        return isset($postData[$fieldName]) && !is_array($postData[$fieldName])
            ? escape((string)$postData[$fieldName])
            : '';
    }
    return isset($getData[$fieldName]) && !is_array($getData[$fieldName])
        ? escape((string)$getData[$fieldName])
        : '';
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GET vs POST Toggle</title>
<style>
:root{
  --bg:#f6f7fb; --card:#fff; --text:#111827; --muted:#6b7280; --border:#e5e7eb;
  --primary:#2563eb; --primaryHover:#1e40af;
  --chipBg:#eff6ff; --chipBd:#bfdbfe; --chipTx:#1d4ed8;
  --shadow:0 12px 30px rgba(0,0,0,.06); --radius:14px;
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
.wrap{max-width:980px;margin:0 auto}
.header{text-align:center;margin-bottom:18px}
.header h1{margin:0 0 6px;font-size:28px}
.header p{margin:0;color:var(--muted);line-height:1.5}

.card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  overflow:hidden;
}
.body{padding:22px}

.topbar{
  display:flex;flex-wrap:wrap;gap:10px 12px;align-items:center;justify-content:space-between;
  margin:0 0 14px;
}
.chips{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
.chip{
  display:inline-flex;align-items:center;gap:8px;
  padding:7px 10px;border-radius:999px;
  background:var(--chipBg);border:1px solid var(--chipBd);color:var(--chipTx);
  font-size:12px;font-weight:700;
}
.chip strong{color:#0f172a}
.link{
  color:var(--muted);
  text-decoration:none;
  font-size:12px;
}
.link:hover{color:#374151}

.grid{display:grid;gap:14px}
@media(min-width:860px){.grid{grid-template-columns:1fr 1fr}}
.panel{
  border:1px solid var(--border);
  border-radius:12px;
  background:#fafafa;
  padding:14px;
}
.panel h2{margin:0 0 10px;font-size:14px}
label{display:block;font-size:13px;font-weight:650;margin:0 0 6px}
input{
  width:100%;padding:10px 12px;border-radius:10px;border:1px solid var(--border);
  background:#fff;color:var(--text);outline:none;transition:.15s ease;font-size:14px;
}
input:focus{
  border-color:rgba(37,99,235,.65);
  box-shadow:0 0 0 4px rgba(37,99,235,.12);
}
.actions{
  display:flex;justify-content:space-between;align-items:center;gap:12px;
  margin-top:14px;padding-top:14px;border-top:1px solid var(--border);
}
.btn{
  border:none;border-radius:12px;background:var(--primary);color:#fff;font-weight:800;
  padding:10px 16px;cursor:pointer;transition:.15s ease;min-width:160px;
}
.btn:hover{background:var(--primaryHover)}
.note{color:var(--muted);font-size:12px;margin:0}
pre{
  margin:0;
  background:#0b1020;
  color:#e5e7eb;
  border-radius:12px;
  padding:12px;
  overflow:auto;
  font-size:12px;
  line-height:1.45;
}
.footer{margin-top:14px;text-align:center;color:var(--muted);font-size:12px}
code{background:#f3f4f6;padding:2px 6px;border-radius:6px}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>GET vs POST Toggle</h1>
    <p>Switch form method to visualize how data is transmitted and which superglobal receives it.</p>
  </div>

  <div class="card">
    <div class="body">

      <div class="topbar">
        <div class="chips">
          <span class="chip">Form mode: <strong><?= escape(strtoupper($formMode)) ?></strong></span>
          <span class="chip">Detected: <strong><?= escape($requestMethod) ?></strong></span>
          <span class="chip"><?= escape($controllerNote) ?></span>
        </div>

        <a class="link" href="?mode=<?= escape($toggleModeForLink) ?>">
          Switch to <?= escape(strtoupper($toggleModeForLink)) ?> mode
        </a>
      </div>

      <div class="grid">
        <div class="panel">
          <h2>Input Form (<?= escape(strtoupper($formMode)) ?>)</h2>

          <form method="<?= escape($formMode) ?>" action="">
            <!-- keep chosen mode across submits -->
            <input type="hidden" name="mode" value="<?= escape($formMode) ?>">

            <div style="display:grid;gap:12px">
              <div>
                <label for="full_name"><?= escape($fields['full_name']) ?></label>
                <input id="full_name" name="full_name" type="text" placeholder="e.g. Jane Doe"
                  value="<?= oldValue('full_name', $getData, $postData, $requestMethod) ?>">
              </div>

              <div>
                <label for="email"><?= escape($fields['email']) ?></label>
                <input id="email" name="email" type="email" placeholder="name@example.com"
                  value="<?= oldValue('email', $getData, $postData, $requestMethod) ?>">
              </div>

              <div>
                <label for="query"><?= escape($fields['query']) ?></label>
                <input id="query" name="query" type="text" placeholder="Type anything…"
                  value="<?= oldValue('query', $getData, $postData, $requestMethod) ?>">
              </div>
            </div>

            <div class="actions">
              <p class="note">
                Tip: In GET mode, data appears in the URL query string.<br>
                In POST mode, data is sent in the request body.
              </p>
              <button class="btn" type="submit">Submit</button>
            </div>
          </form>

          <p class="note" style="margin-top:10px">
            URL example (GET): <code>?full_name=Jane&email=a%40b.com&query=test</code>
          </p>
        </div>

        <div class="panel">
          <h2>Controller Output</h2>

          <p class="note" style="margin:0 0 10px">
            Active superglobal: <code><?= escape($requestMethod === 'POST' ? '$_POST' : '$_GET') ?></code>
          </p>

          <pre><?= dumpArrayForHtml($activeData) ?></pre>

          <div style="margin-top:12px; display:grid; gap:10px">
            <div>
              <p class="note" style="margin:0 0 6px"><strong>$_GET</strong></p>
              <pre><?= dumpArrayForHtml($getData) ?></pre>
            </div>
            <div>
              <p class="note" style="margin:0 0 6px"><strong>$_POST</strong></p>
              <pre><?= dumpArrayForHtml($postData) ?></pre>
            </div>
          </div>

        </div>
      </div>

      <div class="footer">© <?= (int)date('Y') ?> Method Toggle Demo</div>
    </div>
  </div>
</div>
</body>
</html>
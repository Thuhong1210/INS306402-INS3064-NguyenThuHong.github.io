<?php
declare(strict_types=1);

/* =====================================================================
   ex06 – Simple Router / Front Controller
   =====================================================================
   MVC-lite architecture:
     index.php   → Front Controller  (this file)
     views/      → View layer        (home, about, contact, 404)
   All requests go through here; routing is decided by ?page=
   ===================================================================== */

// ── Helpers ──────────────────────────────────────────────────────────
function e(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function loadView(string $view, array $data = []): void {
    $path = __DIR__ . '/views/' . $view . '.php';
    if (!is_file($path)) {
        loadView('404', ['requestedPage' => $view]);
        return;
    }
    // Make $data keys available as variables inside the view
    extract($data, EXTR_SKIP);
    require $path;
}

// ── Router ────────────────────────────────────────────────────────────
$allowedPages = ['home', 'about', 'contact', 'blog', 'services'];

// Sanitise input: lowercase, only alpha chars allowed
$raw  = isset($_GET['page']) ? strtolower(trim((string)$_GET['page'])) : 'home';
$page = preg_replace('/[^a-z]/', '', $raw) ?: 'home';   // strip anything non-alpha

// Dispatch
$data = ['currentPage' => $page];

if (in_array($page, $allowedPages, true)) {
    loadView($page, $data);
} else {
    http_response_code(404);
    loadView('404', array_merge($data, ['requestedPage' => e($raw)]));
}

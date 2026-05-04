<?php
// public/index.php
// Front controller: the browser accesses this file only.

declare(strict_types=1);

require_once __DIR__ . '/../controllers/BookController.php';

$controller = new BookController();
$action = $_GET['action'] ?? 'index';

$allowedActions = ['index', 'show', 'create', 'store', 'edit', 'update', 'delete'];

if (in_array($action, $allowedActions, true)) {
    $controller->$action();
} else {
    http_response_code(404);
    echo 'Page not found.';
}

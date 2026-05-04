<?php
/**
 * public/index.php  –  Front Controller
 *
 * Author  : Hoàng Cẩm Anh  |  MSSV: INS3064
 * Purpose : This is the ONLY file the browser accesses directly.
 *           It reads the ?action= parameter from the URL and routes
 *           the request to the correct ItemController method.
 *
 * How routing works:
 *   index.php                  → ItemController::index()
 *   index.php?action=create    → ItemController::create()
 *   index.php?action=store     → ItemController::store()  (POST)
 *   index.php?action=edit&id=N → ItemController::edit()
 *   index.php?action=update    → ItemController::update() (POST)
 *   index.php?action=delete&id=N → ItemController::delete()
 */

declare(strict_types=1);

// Load the controller (which in turn loads the model)
require_once __DIR__ . '/../controllers/ItemController.php';

// Instantiate the controller
$controller = new ItemController();

// Read the action from the URL; default to 'index' if not provided
$action = $_GET['action'] ?? 'index';

// Only allow these known action names – anything else returns 404.
// This prevents calling arbitrary methods on the controller class.
$allowedActions = ['index', 'create', 'store', 'edit', 'update', 'delete'];

if (in_array($action, $allowedActions, true)) {
    // Dynamically call the matching method on the controller
    $controller->$action();
} else {
    http_response_code(404);
    echo '<p style="font-family:sans-serif;text-align:center;margin-top:3rem;">
            ⚠️ Page not found (404).
          </p>';
}

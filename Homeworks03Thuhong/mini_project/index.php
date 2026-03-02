<?php
// ============================================================
// index.php – Entry point: chuyển hướng thông minh
// ============================================================
session_start();
if (!empty($_SESSION['username']) && !empty($_SESSION['logged_in'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;

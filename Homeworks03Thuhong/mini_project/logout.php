<?php
// ============================================================
// logout.php – Huỷ session an toàn
// ============================================================
session_start();
require_once 'config.php';

// Xoá toàn bộ dữ liệu session
$_SESSION = [];

// Xoá cookie session
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(
        session_name(), '',
        time() - 42000,
        $p['path'], $p['domain'],
        $p['secure'], $p['httponly']
    );
}

session_destroy();

// Chuyển về login với tham số thông báo
header('Location: login.php?msg=logged_out');
exit;

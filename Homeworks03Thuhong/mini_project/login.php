<?php
// ============================================================
// login.php – Đăng nhập với bảo vệ brute-force (≥3 fail = khoá)
// ============================================================
session_start();
require_once 'config.php';

if (!empty($_SESSION['username'])) {
    header('Location: profile.php');
    exit;
}

$error   = '';
$warning = '';
$locked  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = findUser($username);

    if (!$user) {
        $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
    } else {
        // Kiểm tra khoá tài khoản
        if ($user['locked_until'] > time()) {
            $remain = ceil(($user['locked_until'] - time()) / 60);
            $error  = "Tài khoản bị khoá. Vui lòng thử lại sau {$remain} phút.";
            $locked = true;
        } elseif (!password_verify($password, $user['password'])) {
            // Sai mật khẩu → tăng fail_count
            $newFail = ($user['fail_count'] ?? 0) + 1;
            $lockUntil = 0;
            if ($newFail >= MAX_FAIL) {
                $lockUntil = time() + LOCK_SECONDS;
                $error = 'Bạn đã nhập sai ' . MAX_FAIL . ' lần. Tài khoản bị khoá 5 phút!';
                $locked = true;
            } else {
                $remain = MAX_FAIL - $newFail;
                $error  = "Sai mật khẩu. Còn {$remain} lần thử trước khi khoá.";
            }
            updateUser($username, [
                'fail_count'   => $newFail,
                'locked_until' => $lockUntil,
            ]);
        } else {
            // Đăng nhập thành công → reset fail, tạo session
            updateUser($username, ['fail_count' => 0, 'locked_until' => 0]);
            session_regenerate_id(true);  // Chống session fixation
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            header('Location: profile.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đăng nhập – Hệ thống Hồ sơ Sinh viên</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
<link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">

<div class="particles" id="particles"></div>

<div class="auth-card">
    <div class="auth-header">
        <div class="logo-icon">🔐</div>
        <h1>Đăng Nhập</h1>
        <p>Chào mừng trở lại, sinh viên!</p>
    </div>

    <?php if ($error): ?>
        <div class="alert <?= $locked ? 'alert-lock' : 'alert-error' ?>">
            <?= $locked ? '🔒' : '⚠' ?> <?= clean($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="auth-form" autocomplete="off">
        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <div class="input-icon">
                <span>👤</span>
                <input type="text" id="username" name="username"
                       placeholder="Nhập tên đăng nhập"
                       value="<?= clean($_POST['username'] ?? '') ?>"
                       <?= $locked ? 'disabled' : '' ?> required>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <div class="input-icon">
                <span>🔒</span>
                <input type="password" id="password" name="password"
                       placeholder="Nhập mật khẩu"
                       <?= $locked ? 'disabled' : '' ?> required>
                <button type="button" class="eye-btn" onclick="togglePwd()" title="Hiện/ẩn mật khẩu">👁</button>
            </div>
        </div>

        <button type="submit" class="btn-primary btn-full" <?= $locked ? 'disabled' : '' ?>>
            <span>Đăng nhập</span> →
        </button>
    </form>

    <p class="auth-link">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
</div>

<script>
const container = document.getElementById('particles');
for (let i = 0; i < 30; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.cssText = `left:${Math.random()*100}%;top:${Math.random()*100}%;
        animation-delay:${Math.random()*5}s;animation-duration:${4+Math.random()*6}s;
        width:${4+Math.random()*8}px;height:${4+Math.random()*8}px;`;
    container.appendChild(p);
}
function togglePwd() {
    const f = document.getElementById('password');
    f.type = f.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>

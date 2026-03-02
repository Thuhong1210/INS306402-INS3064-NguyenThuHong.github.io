<?php
// ============================================================
// register.php – Đăng ký tài khoản sinh viên
// ============================================================
session_start();
require_once 'config.php';

// Nếu đã đăng nhập thì chuyển thẳng vào profile
if (!empty($_SESSION['username'])) {
    header('Location: profile.php');
    exit;
}

$errors  = [];
$success = '';
$old     = ['username' => '', 'email' => '', 'fullname' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username']  ?? '');
    $email     = trim($_POST['email']     ?? '');
    $fullname  = trim($_POST['fullname']  ?? '');
    $password  = $_POST['password']  ?? '';
    $confirm   = $_POST['confirm']   ?? '';

    $old = compact('username', 'email', 'fullname');

    // ── Validate ─────────────────────────────────────────────
    if (strlen($username) < 4 || strlen($username) > 20) {
        $errors[] = 'Tên đăng nhập phải từ 4–20 ký tự.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Tên đăng nhập chỉ gồm chữ cái, số và dấu gạch dưới.';
    } elseif (findUser($username)) {
        $errors[] = 'Tên đăng nhập đã tồn tại.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ.';
    }

    if (empty($fullname)) {
        $errors[] = 'Họ tên không được để trống.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Mật khẩu phải có ít nhất 1 chữ hoa.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Mật khẩu phải có ít nhất 1 chữ số.';
    }

    if ($password !== $confirm) {
        $errors[] = 'Xác nhận mật khẩu không khớp.';
    }

    // ── Lưu user ─────────────────────────────────────────────
    if (empty($errors)) {
        $users = loadUsers();
        $users[] = [
            'username'     => $username,
            'email'        => $email,
            'fullname'     => $fullname,
            'password'     => password_hash($password, PASSWORD_BCRYPT),
            'bio'          => '',
            'avatar'       => '',
            'created_at'   => date('Y-m-d H:i:s'),
            'fail_count'   => 0,
            'locked_until' => 0,
        ];
        saveUsers($users);
        $success = 'Đăng ký thành công! Bạn có thể đăng nhập ngay.';
        $old = ['username' => '', 'email' => '', 'fullname' => ''];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đăng ký – Hệ thống Hồ sơ Sinh viên</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
<link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">

<div class="particles" id="particles"></div>

<div class="auth-card">
    <div class="auth-header">
        <div class="logo-icon">🎓</div>
        <h1>Đăng Ký</h1>
        <p>Tạo tài khoản hồ sơ sinh viên</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            ✅ <?= $success ?>
            <a href="login.php" class="btn-link">→ Đăng nhập ngay</a>
        </div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-error">
            <strong>⚠ Có lỗi xảy ra:</strong>
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= clean($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="auth-form" autocomplete="off">
        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <div class="input-icon">
                <span>👤</span>
                <input type="text" id="username" name="username"
                       value="<?= clean($old['username']) ?>"
                       placeholder="4–20 ký tự, không dấu" required>
            </div>
        </div>

        <div class="form-group">
            <label for="fullname">Họ và tên</label>
            <div class="input-icon">
                <span>🪪</span>
                <input type="text" id="fullname" name="fullname"
                       value="<?= clean($old['fullname']) ?>"
                       placeholder="Nguyễn Văn A" required>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-icon">
                <span>📧</span>
                <input type="email" id="email" name="email"
                       value="<?= clean($old['email']) ?>"
                       placeholder="example@student.edu.vn" required>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <div class="input-icon">
                <span>🔒</span>
                <input type="password" id="password" name="password"
                       placeholder="≥6 ký tự, có chữ hoa và số" required>
            </div>
            <div class="password-strength" id="strengthBar"></div>
        </div>

        <div class="form-group">
            <label for="confirm">Xác nhận mật khẩu</label>
            <div class="input-icon">
                <span>🔑</span>
                <input type="password" id="confirm" name="confirm"
                       placeholder="Nhập lại mật khẩu" required>
            </div>
        </div>

        <button type="submit" class="btn-primary btn-full">
            <span>Tạo tài khoản</span> →
        </button>
    </form>

    <p class="auth-link">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
</div>

<script>
// Particles animation
const container = document.getElementById('particles');
for (let i = 0; i < 30; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.cssText = `left:${Math.random()*100}%;top:${Math.random()*100}%;
        animation-delay:${Math.random()*5}s;animation-duration:${4+Math.random()*6}s;
        width:${4+Math.random()*8}px;height:${4+Math.random()*8}px;`;
    container.appendChild(p);
}

// Password strength
const pwd = document.getElementById('password');
const bar = document.getElementById('strengthBar');
pwd.addEventListener('input', () => {
    const v = pwd.value;
    let score = 0;
    if (v.length >= 6)              score++;
    if (/[A-Z]/.test(v))           score++;
    if (/[0-9]/.test(v))           score++;
    if (/[^a-zA-Z0-9]/.test(v))   score++;
    const labels = ['', 'Yếu', 'Trung bình', 'Mạnh', 'Rất mạnh'];
    const colors = ['', '#e74c3c', '#f39c12', '#2ecc71', '#27ae60'];
    bar.style.width = (score * 25) + '%';
    bar.style.background = colors[score] || 'transparent';
    bar.title = labels[score] || '';
});
</script>
</body>
</html>

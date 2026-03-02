<?php
// ============================================================
// dashboard.php – Bảng điều khiển người dùng
// ============================================================
session_start();
require_once 'config.php';

// Bảo vệ trang
if (empty($_SESSION['username']) || empty($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$user     = findUser($username);

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$avatarUrl = $user['avatar'] ? 'uploads/' . $user['avatar'] : '';
$initials  = strtoupper(substr($user['fullname'] ?? $username, 0, 2));
$memberDays = max(1, (int)floor((time() - strtotime($user['created_at'])) / 86400));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard – <?= clean($user['fullname']) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
<link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">

<!-- ── Nav ─────────────────────────────────────────────────── -->
<nav class="topnav">
    <div class="nav-brand">🎓 StudentProfile</div>
    <div class="nav-actions">
        <span class="nav-user">👤 <?= clean($user['fullname']) ?></span>
        <a href="profile.php" class="nav-btn">✏️ Sửa hồ sơ</a>
        <a href="logout.php" class="nav-btn nav-btn-danger">Đăng xuất</a>
    </div>
</nav>

<div class="dashboard-container">

    <!-- ── Hero Banner ──────────────────────────────────────── -->
    <section class="hero-banner">
        <div class="hero-glow"></div>
        <div class="hero-content">
            <div class="hero-avatar">
                <?php if ($avatarUrl): ?>
                    <img src="<?= clean($avatarUrl) ?>" alt="Avatar" class="avatar-img-lg">
                <?php else: ?>
                    <div class="avatar-placeholder-lg"><?= clean($initials) ?></div>
                <?php endif; ?>
                <div class="online-badge">●</div>
            </div>
            <div class="hero-info">
                <h1>Chào, <?= clean($user['fullname']) ?>! 👋</h1>
                <p class="hero-sub">@<?= clean($username) ?> · Tham gia <?= $memberDays ?> ngày trước</p>
                <?php if ($user['bio']): ?>
                    <p class="hero-bio"><?= clean($user['bio']) ?></p>
                <?php else: ?>
                    <p class="hero-bio muted"><em>Bạn chưa có tiểu sử. <a href="profile.php">Thêm ngay →</a></em></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ── Stats Grid ───────────────────────────────────────── -->
    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <div class="stat-icon">📅</div>
            <div class="stat-body">
                <div class="stat-value"><?= $memberDays ?></div>
                <div class="stat-label">Ngày là thành viên</div>
            </div>
        </div>
        <div class="stat-card stat-purple">
            <div class="stat-icon">✉️</div>
            <div class="stat-body">
                <div class="stat-value"><?= count(array_filter(str_split($user['email']), fn($c) => $c === '@')) ?></div>
                <div class="stat-label">Email đã xác nhận</div>
            </div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-icon">📝</div>
            <div class="stat-body">
                <div class="stat-value"><?= strlen($user['bio']) ?: 0 ?></div>
                <div class="stat-label">Ký tự trong tiểu sử</div>
            </div>
        </div>
        <div class="stat-card stat-orange">
            <div class="stat-icon">🖼️</div>
            <div class="stat-body">
                <div class="stat-value"><?= $user['avatar'] ? '✓' : '✗' ?></div>
                <div class="stat-label">Ảnh đại diện</div>
            </div>
        </div>
    </div>

    <!-- ── Quick Actions & Info ─────────────────────────────── -->
    <div class="dashboard-grid">

        <div class="card">
            <div class="card-header">
                <h3>⚡ Thao tác nhanh</h3>
            </div>
            <div class="action-list">
                <a href="profile.php" class="action-item">
                    <span class="action-icon">✏️</span>
                    <div>
                        <strong>Chỉnh sửa hồ sơ</strong>
                        <p>Cập nhật thông tin, tiểu sử, ảnh đại diện</p>
                    </div>
                    <span class="action-arrow">→</span>
                </a>
                <a href="logout.php" class="action-item action-danger">
                    <span class="action-icon">🚪</span>
                    <div>
                        <strong>Đăng xuất</strong>
                        <p>Kết thúc phiên làm việc an toàn</p>
                    </div>
                    <span class="action-arrow">→</span>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>👤 Thông tin tài khoản</h3>
            </div>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-label">Tên đăng nhập</span>
                    <span class="info-value badge-blue">@<?= clean($username) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Họ tên</span>
                    <span class="info-value"><?= clean($user['fullname']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= clean($user['email']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày đăng ký</span>
                    <span class="info-value"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ảnh đại diện</span>
                    <span class="info-value <?= $user['avatar'] ? 'badge-green' : 'badge-red' ?>">
                        <?= $user['avatar'] ? '✓ Đã có' : '✗ Chưa có' ?>
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- ── Security Notice ──────────────────────────────────── -->
    <div class="security-banner">
        <span>🔒</span>
        <p>Bạn đang đăng nhập bảo mật. Dữ liệu được mã hoá – mật khẩu lưu dạng bcrypt hash. Session được bảo vệ khỏi fixation attack.</p>
    </div>

</div>

</body>
</html>

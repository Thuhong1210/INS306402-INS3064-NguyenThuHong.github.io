<?php
// ============================================================
// profile.php – Trang hồ sơ cá nhân (yêu cầu đăng nhập)
// ============================================================
session_start();
require_once 'config.php';

// ── Kiểm tra quyền truy cập ─────────────────────────────────
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

$errors  = [];
$success = '';

// ── Xử lý cập nhật hồ sơ ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $fullname = trim($_POST['fullname'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $bio      = trim($_POST['bio']      ?? '');

        // Validate
        if (empty($fullname)) $errors[] = 'Họ tên không được để trống.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';
        if (strlen($bio) > 500) $errors[] = 'Tiểu sử tối đa 500 ký tự.';

        // Xử lý avatar upload
        $avatarPath = $user['avatar'];
        if (!empty($_FILES['avatar']['name'])) {
            $file     = $_FILES['avatar'];
            $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $blocked  = ['exe', 'pdf', 'php', 'js', 'sh', 'bat', 'cmd'];

            if (in_array($ext, $blocked)) {
                $errors[] = "Không cho phép upload file .$ext. Chỉ chấp nhận ảnh (jpg/png/gif/webp).";
            } elseif (!in_array($ext, $allowed)) {
                $errors[] = "Định dạng file không hợp lệ. Chỉ chấp nhận: " . implode(', ', $allowed);
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $errors[] = 'Ảnh tối đa 2MB.';
            } else {
                // Kiểm tra MIME type thực sự
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime  = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
                $allowedMimes = ['image/jpeg','image/png','image/gif','image/webp'];
                if (!in_array($mime, $allowedMimes)) {
                    $errors[] = 'Nội dung file không phải ảnh hợp lệ.';
                } else {
                    $newName    = $username . '_' . time() . '.' . $ext;
                    $dest       = UPLOAD_DIR . $newName;
                    if (move_uploaded_file($file['tmp_name'], $dest)) {
                        // Xoá ảnh cũ nếu có
                        if ($avatarPath && file_exists(UPLOAD_DIR . $avatarPath)) {
                            unlink(UPLOAD_DIR . $avatarPath);
                        }
                        $avatarPath = $newName;
                    } else {
                        $errors[] = 'Lỗi khi lưu ảnh. Vui lòng thử lại.';
                    }
                }
            }
        }

        if (empty($errors)) {
            updateUser($username, [
                'fullname' => $fullname,
                'email'    => $email,
                'bio'      => $bio,       // Lưu raw để clean khi hiển thị (chống XSS)
                'avatar'   => $avatarPath,
            ]);
            $user    = findUser($username);
            $success = 'Hồ sơ đã được cập nhật thành công! 🎉';
        }
    }
}

// ─ Avatar URL helper ─────────────────────────────────────────
$avatarUrl = $user['avatar'] ? 'uploads/' . $user['avatar'] : '';
$initials  = strtoupper(substr($user['fullname'] ?? $username, 0, 2));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hồ sơ – <?= clean($user['fullname']) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
<link rel="stylesheet" href="style.css">
</head>
<body class="profile-body">

<!-- ── Navigation ─────────────────────────────────────── -->
<nav class="topnav">
    <div class="nav-brand">🎓 StudentProfile</div>
    <div class="nav-actions">
        <span class="nav-user">👤 <?= clean($user['fullname']) ?></span>
        <a href="dashboard.php" class="nav-btn">Dashboard</a>
        <a href="logout.php" class="nav-btn nav-btn-danger">Đăng xuất</a>
    </div>
</nav>

<div class="profile-layout">

    <!-- ── Sidebar ──────────────────────────────────────── -->
    <aside class="profile-sidebar">
        <div class="avatar-wrapper">
            <?php if ($avatarUrl): ?>
                <img src="<?= clean($avatarUrl) ?>" alt="Avatar" class="avatar-img" id="avatarPreview">
            <?php else: ?>
                <div class="avatar-placeholder" id="avatarPreview"><?= clean($initials) ?></div>
            <?php endif; ?>
            <label class="avatar-overlay" for="avatar" title="Đổi ảnh">
                📷 Đổi ảnh
            </label>
        </div>

        <h2 class="sidebar-name"><?= clean($user['fullname']) ?></h2>
        <p class="sidebar-username">@<?= clean($username) ?></p>
        <p class="sidebar-email">✉ <?= clean($user['email']) ?></p>

        <div class="sidebar-stats">
            <div class="stat-item">
                <span class="stat-num"><?= date('Y') - 2020 ?></span>
                <span class="stat-label">Năm học</span>
            </div>
            <div class="stat-item">
                <span class="stat-num"><?= strlen($user['bio']) ?></span>
                <span class="stat-label">Ký tự bio</span>
            </div>
            <div class="stat-item">
                <span class="stat-num"><?= date('d/m', strtotime($user['created_at'])) ?></span>
                <span class="stat-label">Ngày tạo</span>
            </div>
        </div>
    </aside>

    <!-- ── Main content ─────────────────────────────────── -->
    <main class="profile-main">

        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= clean($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <strong>⚠ Có lỗi:</strong>
                <ul><?php foreach ($errors as $e): ?><li><?= clean($e) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3>✏️ Chỉnh sửa hồ sơ</h3>
                <p>Cập nhật thông tin cá nhân của bạn</p>
            </div>

            <form method="POST" enctype="multipart/form-data" class="profile-form">
                <input type="hidden" name="action" value="update_profile">

                <!-- Avatar field (hidden, triggered by sidebar label) -->
                <input type="file" id="avatar" name="avatar" accept="image/*"
                       style="display:none" onchange="previewAvatar(this)">

                <div class="form-row">
                    <div class="form-group">
                        <label for="fullname">Họ và tên *</label>
                        <input type="text" id="fullname" name="fullname"
                               value="<?= clean($user['fullname']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email"
                               value="<?= clean($user['email']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="bio">
                        Tiểu sử
                        <span class="bio-counter" id="bioCounter">
                            <?= strlen($user['bio']) ?>/500
                        </span>
                    </label>
                    <textarea id="bio" name="bio" rows="5"
                              maxlength="500"
                              placeholder="Giới thiệu bản thân, mục tiêu học tập, sở thích..."
                              oninput="document.getElementById('bioCounter').textContent=this.value.length+'/500'"
                    ><?= clean($user['bio']) ?></textarea>
                    <small class="form-hint">⚠ Không nhập HTML/script – nội dung sẽ được làm sạch tự động.</small>
                </div>

                <div class="form-group avatar-upload-group">
                    <label>Ảnh đại diện</label>
                    <div class="upload-hint">
                        Nhấp vào ảnh bên trái để chọn file (jpg/png/gif/webp, tối đa 2MB).<br>
                        <strong>Không chấp nhận:</strong> .exe, .pdf, .php và các file không phải ảnh.
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">💾 Lưu thay đổi</button>
                    <a href="dashboard.php" class="btn-secondary">← Dashboard</a>
                </div>
            </form>
        </div>

        <!-- Bio display preview -->
        <?php if ($user['bio']): ?>
        <div class="card bio-card">
            <h3>📝 Tiểu sử hiện tại</h3>
            <p><?= clean($user['bio']) ?></p>
        </div>
        <?php endif; ?>

    </main>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            // Replace or create img element
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'avatar-img';
                img.id = 'avatarPreview';
                preview.parentNode.replaceChild(img, preview);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>

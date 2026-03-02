<?php
// ============================================================
// config.php – Cấu hình trung tâm của hệ thống
// ============================================================

define('DATA_DIR',    __DIR__ . '/data/');
define('UPLOAD_DIR',  __DIR__ . '/uploads/');
define('USERS_FILE',  DATA_DIR . 'users.json');
define('MAX_FAIL',    3);          // Số lần thất bại tối đa trước khi khoá đăng nhập
define('LOCK_SECONDS', 300);       // 5 phút khoá

// Tạo thư mục lưu trữ nếu chưa có
if (!is_dir(DATA_DIR))   mkdir(DATA_DIR,   0755, true);
if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

// Khởi tạo file users.json nếu chưa tồn tại
if (!file_exists(USERS_FILE)) {
    file_put_contents(USERS_FILE, json_encode([], JSON_PRETTY_PRINT));
}

// ── Helper: đọc danh sách users ─────────────────────────────
function loadUsers(): array {
    $raw = file_get_contents(USERS_FILE);
    return json_decode($raw, true) ?? [];
}

// ── Helper: ghi danh sách users ─────────────────────────────
function saveUsers(array $users): void {
    file_put_contents(USERS_FILE, json_encode(array_values($users), JSON_PRETTY_PRINT));
}

// ── Helper: tìm user theo username ─────────────────────────
function findUser(string $username): ?array {
    foreach (loadUsers() as $u) {
        if ($u['username'] === $username) return $u;
    }
    return null;
}

// ── Helper: cập nhật user (theo username) ───────────────────
function updateUser(string $username, array $newData): void {
    $users = loadUsers();
    foreach ($users as &$u) {
        if ($u['username'] === $username) {
            $u = array_merge($u, $newData);
            break;
        }
    }
    saveUsers($users);
}

// ── Helper: xoá XSS ─────────────────────────────────────────
function clean(string $str): string {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

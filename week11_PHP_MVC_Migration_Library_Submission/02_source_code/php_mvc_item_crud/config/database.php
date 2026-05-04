<?php
/**
 * config/database.php
 *
 * Author  : Hoàng Cẩm Anh  |  MSSV: INS3064
 * Purpose : Provide a single, reusable PDO database connection.
 *           Every Model file calls getConnection() instead of
 *           writing the PDO block again.
 */

declare(strict_types=1);

/**
 * getConnection – opens and returns a PDO connection.
 *
 * @return PDO  A configured PDO instance.
 */
function getConnection(): PDO
{
    // ── Database credentials ──────────────────────────────────────
    $host   = 'localhost';
    $dbname = 'mvc_item_db';
    $user   = 'root';
    $pass   = '';                // default XAMPP password is empty
    // ─────────────────────────────────────────────────────────────

    try {
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass);

        // Throw exceptions on database errors instead of silent failures
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Return rows as associative arrays by default
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;

    } catch (PDOException $e) {
        // Stop execution and show a readable error message
        die('Database connection failed: ' . $e->getMessage());
    }
}

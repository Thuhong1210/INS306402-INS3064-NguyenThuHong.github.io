<?php
// config/database.php
// Reusable PDO connection for the MVC application.

declare(strict_types=1);

function getConnection(): PDO
{
    $host = 'localhost';
    $dbname = 'mvc_library_db';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

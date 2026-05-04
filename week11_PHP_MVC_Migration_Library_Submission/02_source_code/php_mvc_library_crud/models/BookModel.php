<?php
// models/BookModel.php
// Model: contains database queries and data logic only.

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class BookModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM books ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO books (title, author, category, status) VALUES (?, ?, ?, ?)'
        );

        return $stmt->execute([
            $data['title'],
            $data['author'],
            $data['category'],
            $data['status'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE books SET title = ?, author = ?, category = ?, status = ? WHERE id = ?'
        );

        return $stmt->execute([
            $data['title'],
            $data['author'],
            $data['category'],
            $data['status'],
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM books WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

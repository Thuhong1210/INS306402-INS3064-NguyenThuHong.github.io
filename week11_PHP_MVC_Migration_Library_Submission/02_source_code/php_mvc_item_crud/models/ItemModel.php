<?php
/**
 * models/ItemModel.php
 *
 * Author  : Hoàng Cẩm Anh  |  MSSV: INS3064
 * Purpose : Model layer – contains ONLY database logic.
 *           No HTML, no $_POST, no business routing here.
 *
 * This class provides CRUD methods for the `items` table
 * using PDO prepared statements to prevent SQL injection.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class ItemModel
{
    // The PDO database connection shared across all methods
    private PDO $pdo;

    /**
     * Constructor – creates the PDO connection when the model is instantiated.
     */
    public function __construct()
    {
        $this->pdo = getConnection();
    }

    // ─────────────────────────────────────────────────────────────
    // READ – retrieve records
    // ─────────────────────────────────────────────────────────────

    /**
     * getAll – fetch every item, newest first.
     *
     * @return array  Array of associative arrays (one per row).
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM items ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    /**
     * getById – fetch a single item by its primary key.
     *
     * @param int $id  The item ID (already cast to int by the controller).
     * @return array|false  The row as an array, or false if not found.
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM items WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ─────────────────────────────────────────────────────────────
    // CREATE – insert a new record
    // ─────────────────────────────────────────────────────────────

    /**
     * create – insert a new item row.
     *
     * @param array $data  Must contain keys: 'name', 'description'.
     * @return bool  True on success.
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO items (name, description) VALUES (?, ?)'
        );

        return $stmt->execute([
            $data['name'],
            $data['description'],
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // UPDATE – modify an existing record
    // ─────────────────────────────────────────────────────────────

    /**
     * update – change the name and description of an existing item.
     *
     * @param int   $id    Row to update.
     * @param array $data  New values: 'name', 'description'.
     * @return bool  True on success.
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE items SET name = ?, description = ? WHERE id = ?'
        );

        return $stmt->execute([
            $data['name'],
            $data['description'],
            $id,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // DELETE – remove a record
    // ─────────────────────────────────────────────────────────────

    /**
     * delete – permanently remove an item from the database.
     *
     * @param int $id  The item to delete.
     * @return bool  True on success.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM items WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

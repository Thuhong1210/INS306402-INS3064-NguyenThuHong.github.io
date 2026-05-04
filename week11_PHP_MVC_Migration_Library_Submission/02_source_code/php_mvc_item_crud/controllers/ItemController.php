<?php
/**
 * controllers/ItemController.php
 *
 * Author  : Hoàng Cẩm Anh  |  MSSV: INS3064
 * Purpose : Controller layer – reads HTTP requests, validates input,
 *           calls the Model, and loads the correct View.
 *
 * Rules:
 *   - No SQL queries here (that belongs in the Model).
 *   - No raw HTML output (that belongs in Views).
 *   - After every POST action, redirect to avoid duplicate submissions.
 */

declare(strict_types=1);

require_once __DIR__ . '/../models/ItemModel.php';

class ItemController
{
    // The model that handles all database work
    private ItemModel $model;

    /**
     * Constructor – creates one shared Model instance.
     */
    public function __construct()
    {
        $this->model = new ItemModel();
    }

    // ─────────────────────────────────────────────────────────────
    // Action: index – show the full item list
    // ─────────────────────────────────────────────────────────────

    /**
     * index – fetch all items and display the list view.
     */
    public function index(): void
    {
        // Get every item from the database (newest first)
        $items = $this->model->getAll();

        // Load the list view; $items is available inside the view
        require __DIR__ . '/../views/item/index.php';
    }

    // ─────────────────────────────────────────────────────────────
    // Action: create – show the "Add New Item" form
    // ─────────────────────────────────────────────────────────────

    /**
     * create – display an empty form for adding a new item.
     */
    public function create(): void
    {
        // Pre-fill $data with empty values so the view does not crash
        $data   = ['name' => '', 'description' => ''];
        $errors = [];

        require __DIR__ . '/../views/item/create.php';
    }

    // ─────────────────────────────────────────────────────────────
    // Action: store – handle the "Add New Item" form submission (POST)
    // ─────────────────────────────────────────────────────────────

    /**
     * store – validate the POST data, save the new item, then redirect.
     */
    public function store(): void
    {
        // 1. Read and clean the form input
        $data = $this->readFormData();

        // 2. Validate – collect any error messages
        $errors = $this->validateData($data);

        // 3. If there are errors, reload the form with the user's input
        if (!empty($errors)) {
            require __DIR__ . '/../views/item/create.php';
            return;
        }

        // 4. No errors – save to database and redirect (PRG pattern)
        $this->model->create($data);
        header('Location: index.php?action=index');
        exit;
    }

    // ─────────────────────────────────────────────────────────────
    // Action: edit – show the "Edit Item" form pre-filled with data
    // ─────────────────────────────────────────────────────────────

    /**
     * edit – load an existing item and display the edit form.
     */
    public function edit(): void
    {
        // Cast the URL parameter to int to prevent injection
        $id   = (int) ($_GET['id'] ?? 0);
        $item = $this->model->getById($id);

        // If the item does not exist, return a 404
        if (!$item) {
            http_response_code(404);
            echo '<p style="font-family:sans-serif;text-align:center;">Item not found.</p>';
            return;
        }

        // Pass the item data into the view as $data
        $data   = $item;
        $errors = [];

        require __DIR__ . '/../views/item/edit.php';
    }

    // ─────────────────────────────────────────────────────────────
    // Action: update – handle the "Edit Item" form submission (POST)
    // ─────────────────────────────────────────────────────────────

    /**
     * update – validate POST data, update the record, then redirect.
     */
    public function update(): void
    {
        // The hidden input in the edit form sends the item ID
        $id   = (int) ($_POST['id'] ?? 0);
        $data = $this->readFormData();

        $errors = $this->validateData($data);

        // On error, reload the edit form with the user's current input
        if (!empty($errors)) {
            $data['id'] = $id;   // keep the ID so the form action is correct
            require __DIR__ . '/../views/item/edit.php';
            return;
        }

        // Save changes and redirect
        $this->model->update($id, $data);
        header('Location: index.php?action=index');
        exit;
    }

    // ─────────────────────────────────────────────────────────────
    // Action: delete – remove an item by ID (GET with confirm)
    // ─────────────────────────────────────────────────────────────

    /**
     * delete – delete the item, then redirect back to the list.
     */
    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $this->model->delete($id);
        header('Location: index.php?action=index');
        exit;
    }

    // ─────────────────────────────────────────────────────────────
    // Private helpers – not accessible from outside this class
    // ─────────────────────────────────────────────────────────────

    /**
     * readFormData – sanitize and return POST input as an array.
     *
     * @return array  Keys: 'name', 'description'.
     */
    private function readFormData(): array
    {
        return [
            'name'        => trim($_POST['name']        ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ];
    }

    /**
     * validateData – check required fields and return any error messages.
     *
     * @param array $data  The form data array.
     * @return array       An array of error strings (empty = no errors).
     */
    private function validateData(array $data): array
    {
        $errors = [];

        // Item name is required
        if ($data['name'] === '') {
            $errors[] = 'Item name is required.';
        }

        // Enforce a reasonable maximum length check
        if (strlen($data['name']) > 150) {
            $errors[] = 'Item name must be 150 characters or fewer.';
        }

        return $errors;
    }
}

<?php
// controllers/BookController.php
// Controller: handles HTTP requests, calls the Model, and loads Views.

declare(strict_types=1);

require_once __DIR__ . '/../models/BookModel.php';

class BookController
{
    private BookModel $model;

    public function __construct()
    {
        $this->model = new BookModel();
    }

    public function index(): void
    {
        $books = $this->model->getAll();
        require __DIR__ . '/../views/books/index.php';
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $book = $this->model->getById($id);

        if (!$book) {
            http_response_code(404);
            echo 'Book not found.';
            return;
        }

        require __DIR__ . '/../views/books/show.php';
    }

    public function create(): void
    {
        $data = [
            'title' => '',
            'author' => '',
            'category' => '',
            'status' => 'Available',
        ];
        $errors = [];

        require __DIR__ . '/../views/books/create.php';
    }

    public function store(): void
    {
        $data = $this->getFormData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            require __DIR__ . '/../views/books/create.php';
            return;
        }

        $this->model->create($data);
        header('Location: index.php?action=index');
        exit;
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $book = $this->model->getById($id);

        if (!$book) {
            http_response_code(404);
            echo 'Book not found.';
            return;
        }

        $data = $book;
        $errors = [];

        require __DIR__ . '/../views/books/edit.php';
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $data = $this->getFormData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $data['id'] = $id;
            require __DIR__ . '/../views/books/edit.php';
            return;
        }

        $this->model->update($id, $data);
        header('Location: index.php?action=index');
        exit;
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $this->model->delete($id);
        header('Location: index.php?action=index');
        exit;
    }

    private function getFormData(): array
    {
        return [
            'title' => trim($_POST['title'] ?? ''),
            'author' => trim($_POST['author'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'status' => trim($_POST['status'] ?? 'Available'),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        $allowedStatuses = ['Available', 'Borrowed', 'Maintenance'];

        if ($data['title'] === '') {
            $errors[] = 'Book title is required.';
        }

        if ($data['author'] === '') {
            $errors[] = 'Author is required.';
        }

        if (!in_array($data['status'], $allowedStatuses, true)) {
            $errors[] = 'Invalid book status.';
        }

        return $errors;
    }
}

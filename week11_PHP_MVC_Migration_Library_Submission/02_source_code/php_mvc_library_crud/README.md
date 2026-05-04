# PHP MVC Library CRUD - No Framework

This is a simple PHP MVC CRUD application for a Library Book Borrowing System.

## Requirements
- XAMPP
- Apache
- MySQL
- PHP 8.0+

## Setup Steps
1. Copy this folder to `C:\xampp\htdocs\php_mvc_library_crud`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin.
4. Import `database.sql`.
5. Visit: `http://localhost/php_mvc_library_crud/public/index.php`

## MVC Structure
- `models/BookModel.php`: database queries only.
- `controllers/BookController.php`: reads requests, calls model, loads views.
- `views/books/`: HTML templates only.
- `public/index.php`: front controller and simple router.
- `config/database.php`: reusable PDO connection.

# Item Manager – PHP MVC CRUD (No Framework)

**Author:** Hoàng Cẩm Anh  
**Assignment:** Week 11 – PHP MVC Migration

---

## Project Description

A plain PHP web application refactored into a strict **Model-View-Controller (MVC)**
architecture without using any external framework.

The application manages a simple **Item** list (name + description) and
demonstrates full CRUD (Create / Read / Update / Delete) functionality using
PDO prepared statements.

---

## Folder Structure

```
php_mvc_item_crud/
├── config/
│   └── database.php          ← PDO connection factory (single source of truth)
├── models/
│   └── ItemModel.php         ← Database layer (ONLY SQL here)
├── controllers/
│   └── ItemController.php    ← Request handling, validation, routing
├── views/
│   ├── item/
│   │   ├── index.php         ← List all items
│   │   ├── create.php        ← Add new item form
│   │   └── edit.php          ← Edit existing item form
│   └── partials/
│       ├── header.php        ← Reusable HTML head + navbar
│       └── footer.php        ← Reusable footer with author credit
├── public/
│   ├── index.php             ← Front controller (ONLY browser entry point)
│   └── assets/
│       └── style.css         ← All CSS (no framework)
└── database.sql              ← SQL to create the database and seed data
```

---

## Setup Instructions

1. **Import the database**
   - Open phpMyAdmin → Import → select `database.sql`
   - OR run: `mysql -u root -p < database.sql`

2. **Copy the project folder**
   - Place `php_mvc_item_crud/` inside XAMPP's `htdocs/` directory.

3. **Start XAMPP**
   - Make sure Apache and MySQL are running.

4. **Open in browser**
   ```
   http://localhost/php_mvc_item_crud/public/index.php
   ```

---

## URL Actions

| URL                                     | Controller Action         |
|-----------------------------------------|---------------------------|
| `index.php`                             | `ItemController::index()` |
| `index.php?action=create`               | `ItemController::create()`|
| `index.php?action=store` (POST)         | `ItemController::store()` |
| `index.php?action=edit&id=N`            | `ItemController::edit()`  |
| `index.php?action=update` (POST)        | `ItemController::update()`|
| `index.php?action=delete&id=N`          | `ItemController::delete()`|

---

## Security Highlights

- All SQL uses **PDO prepared statements** (no raw concatenation).
- All view output is wrapped in `htmlspecialchars()` to prevent XSS.
- URL `id` parameters are cast to `(int)` before use.
- Every POST action redirects after success (Post-Redirect-Get pattern).
- Only known actions are allowed; anything else returns HTTP 404.

---

*Developed by Hoàng Cẩm Anh – PHP MVC Demo – No Framework*

<!DOCTYPE html>
<!--
    views/partials/header.php
    Author  : Hoàng Cẩm Anh
    Purpose : Reusable page header / navbar included at the top of every view.
              Contains the <head> section and the sticky site navigation bar.
-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Item Management System – a simple PHP MVC CRUD demo.">
    <title>Item Manager · MVC Demo</title>

    <!-- Google Fonts – Inter for clean, modern typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Application stylesheet (relative to public/index.php) -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- ── Sticky navigation bar ─────────────────────────────────── -->
<header class="site-header">
    <!-- Brand / logo area -->
    <span class="logo">Item <span>Manager</span></span>

    <!-- Navigation links -->
    <nav>
        <a href="index.php?action=index">All Items</a>
        <a href="index.php?action=create">+ Add Item</a>
    </nav>
</header>

<!-- ── Main content wrapper (closed in footer.php) ───────────── -->
<main class="container">

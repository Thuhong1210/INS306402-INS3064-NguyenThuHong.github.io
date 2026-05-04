<?php require __DIR__ . '/../partials/header.php'; ?>

<section class="card">
    <div class="section-title">
        <h2>All Books</h2>
        <a class="btn" href="index.php?action=create">+ Add New Book</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($books)): ?>
                <tr>
                    <td colspan="6">No books found.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $book['id']) ?></td>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= htmlspecialchars($book['category']) ?></td>
                    <td><span class="badge"><?= htmlspecialchars($book['status']) ?></span></td>
                    <td class="actions">
                        <a href="index.php?action=show&id=<?= (int) $book['id'] ?>">View</a>
                        <a href="index.php?action=edit&id=<?= (int) $book['id'] ?>">Edit</a>
                        <a href="index.php?action=delete&id=<?= (int) $book['id'] ?>" onclick="return confirm('Delete this book?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>

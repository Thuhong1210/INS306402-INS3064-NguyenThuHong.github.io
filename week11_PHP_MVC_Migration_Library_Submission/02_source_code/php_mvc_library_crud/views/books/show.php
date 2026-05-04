<?php require __DIR__ . '/../partials/header.php'; ?>

<section class="card detail-card">
    <h2><?= htmlspecialchars($book['title']) ?></h2>
    <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($book['category']) ?></p>
    <p><strong>Status:</strong> <span class="badge"><?= htmlspecialchars($book['status']) ?></span></p>
    <p><strong>Created at:</strong> <?= htmlspecialchars($book['created_at']) ?></p>

    <div class="form-actions">
        <a class="btn" href="index.php?action=edit&id=<?= (int) $book['id'] ?>">Edit</a>
        <a href="index.php?action=index">Back to list</a>
    </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>

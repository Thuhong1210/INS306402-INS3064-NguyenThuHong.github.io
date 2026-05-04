<?php require __DIR__ . '/../partials/header.php'; ?>

<section class="card form-card">
    <h2>Add New Book</h2>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=store">
        <label>Title
            <input type="text" name="title" value="<?= htmlspecialchars($data['title'] ?? '') ?>" required>
        </label>

        <label>Author
            <input type="text" name="author" value="<?= htmlspecialchars($data['author'] ?? '') ?>" required>
        </label>

        <label>Category
            <input type="text" name="category" value="<?= htmlspecialchars($data['category'] ?? '') ?>">
        </label>

        <label>Status
            <select name="status">
                <?php foreach (['Available', 'Borrowed', 'Maintenance'] as $status): ?>
                    <option value="<?= $status ?>" <?= (($data['status'] ?? '') === $status) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($status) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <div class="form-actions">
            <button type="submit">Save</button>
            <a href="index.php?action=index">Cancel</a>
        </div>
    </form>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>

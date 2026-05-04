<?php
/**
 * views/item/create.php
 *
 * Author  : Hoàng Cẩm Anh  |  MSSV: INS3064
 * Purpose : "Add New Item" form view.
 *
 * Variables received from ItemController::create() / store():
 *   $data    array  – current form values (empty on first load)
 *   $errors  array  – validation error messages (empty on first load)
 *
 * Rules:
 *   - No SQL here.
 *   - All values in inputs are escaped with htmlspecialchars().
 *   - Form posts to index.php?action=store
 */
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<section class="card form-card">

    <h2>Add New Item</h2>

    <!-- ── Validation error messages ──────────────────────────── -->
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- ── Add form – posts to the store action ────────────────── -->
    <form method="POST" action="index.php?action=store">

        <!-- Item name (required field) -->
        <div class="form-group">
            <label for="name">Item Name *</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($data['name'] ?? '') ?>"
                placeholder="e.g. Wireless Keyboard"
                maxlength="150"
                required
            >
        </div>

        <!-- Description (optional) -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="Short description of the item (optional)"
            ><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
        </div>

        <!-- Form action buttons -->
        <div class="form-actions">
            <button class="btn" type="submit">Save Item</button>
            <a class="btn btn-secondary" href="index.php?action=index">Cancel</a>
        </div>

    </form>

</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<?php
/**
 * views/item/edit.php
 *
 * Author  : Hoàng Cẩm Anh  |  MSSV: INS3064
 * Purpose : "Edit Item" form view – pre-fills the form with existing data.
 *
 * Variables received from ItemController::edit() / update():
 *   $data    array  – existing item values (keys: id, name, description)
 *   $errors  array  – validation error messages (empty on first load)
 *
 * Rules:
 *   - No SQL here.
 *   - The item ID is stored in a hidden input and must be htmlspecialchars()'d.
 *   - All values are escaped with htmlspecialchars() before output.
 *   - Form posts to index.php?action=update
 */
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<section class="card form-card">

    <h2>Edit Item</h2>

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

    <!-- ── Edit form – posts to the update action ──────────────── -->
    <form method="POST" action="index.php?action=update">

        <!-- Hidden field: sends the item ID back to the controller -->
        <input
            type="hidden"
            name="id"
            value="<?= htmlspecialchars((string) ($data['id'] ?? 0)) ?>"
        >

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
            <button class="btn" type="submit">Update Item</button>
            <a class="btn btn-secondary" href="index.php?action=index">Cancel</a>
        </div>

    </form>

</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>

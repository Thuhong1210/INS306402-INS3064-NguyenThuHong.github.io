<?php
/**
 * views/item/index.php
 *
 * Author  : Hoàng Cẩm Anh  |  MSSV: INS3064
 * Purpose : List view – displays all items in a styled table.
 *
 * Variables received from ItemController::index():
 *   $items  array  – all rows from the items table
 *
 * Rules:
 *   - No SQL queries here.
 *   - Every user-supplied value is wrapped in htmlspecialchars() for XSS safety.
 */
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<section class="card">

    <!-- ── Section heading + "Add Item" button ─────────────────── -->
    <div class="section-title">
        <h2>All Items</h2>
        <a class="btn" href="index.php?action=create">+ Add Item</a>
    </div>

    <!-- ── Data table ──────────────────────────────────────────── -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php if (empty($items)): ?>
                <!-- Empty-state message when no records exist -->
                <tr>
                    <td colspan="4" class="empty-state">No data available.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($items as $item): ?>
                <tr>
                    <!-- ID column – cast to int for safety -->
                    <td><?= (int) $item['id'] ?></td>

                    <!-- Name – escaped to prevent XSS -->
                    <td><?= htmlspecialchars($item['name']) ?></td>

                    <!-- Description – truncate long text for readability -->
                    <td><?= htmlspecialchars(mb_strimwidth($item['description'], 0, 80, '…')) ?></td>

                    <!-- Action links: Edit and Delete -->
                    <td>
                        <div class="actions">
                            <a class="action-link edit"
                               href="index.php?action=edit&id=<?= (int) $item['id'] ?>">
                                Edit
                            </a>

                            <!-- The onclick confirms before the delete request is sent -->
                            <a class="action-link delete"
                               href="index.php?action=delete&id=<?= (int) $item['id'] ?>"
                               onclick="return confirm('Delete this item? This cannot be undone.')">
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>

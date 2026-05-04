<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8fafc; }
        .container { background: white; padding: 24px; border-radius: 12px; max-width: 720px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .card { border: 1px solid #d0d7de; padding: 20px; border-radius: 8px; }
        button { margin-top: 12px; padding: 8px 12px; background: #1f4e79; color: white; border: none; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Request Details</h1>

    <div class="card">
        <h2><?= htmlspecialchars($request->getTitle()) ?></h2>
        <p><strong>Room:</strong> <?= htmlspecialchars($request->getRoom()) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($request->getStatus()) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($request->getDescription()) ?></p>
        <p><strong>Created by:</strong> <?= htmlspecialchars($request->getCreatedBy()) ?></p>
    </div>

    <h3>Update Status</h3>
    <form method="POST" action="?page=update-status&id=<?= $request->getId() ?>">
        <select name="status">
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Done">Done</option>
        </select>
        <button type="submit">Update</button>
    </form>

    <p><a href="?page=requests">Back to list</a></p>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Campus Service Requests</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8fafc; }
        .container { background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #d0d7de; padding: 10px; text-align: left; }
        th { background: #1f4e79; color: white; }
        .btn { display: inline-block; padding: 8px 12px; background: #1f4e79; color: #fff; text-decoration: none; border-radius: 4px; }
        .links a { margin-right: 12px; }
    </style>
</head>
<body>
<div class="container">
    <h1><?= htmlspecialchars($title ?? 'Campus Service Requests') ?></h1>
    <p class="links">
        <a class="btn" href="?page=create">Submit New Request</a>
        <a href="?page=staff-requests">Staff View</a>
    </p>

    <table>
        <thead>
            <tr><th>ID</th><th>Title</th><th>Room</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $request->getId()) ?></td>
                    <td><?= htmlspecialchars($request->getTitle()) ?></td>
                    <td><?= htmlspecialchars($request->getRoom()) ?></td>
                    <td><?= htmlspecialchars($request->getStatus()) ?></td>
                    <td><a href="?page=show&id=<?= $request->getId() ?>">View details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

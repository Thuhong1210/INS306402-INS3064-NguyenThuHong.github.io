<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Request</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8fafc; }
        .container { background: white; padding: 24px; border-radius: 12px; max-width: 640px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { margin-top: 12px; padding: 8px 12px; background: #1f4e79; color: white; border: none; border-radius: 4px; }
        .error { color: darkred; background: #fff0f0; padding: 8px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Submit a Campus Service Request</h1>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="?page=store">
        <label for="title">Title</label>
        <input id="title" name="title" type="text" placeholder="Projector not working">

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" placeholder="Describe the problem"></textarea>

        <label for="room">Room</label>
        <input id="room" name="room" type="text" placeholder="A201">

        <button type="submit">Submit Request</button>
    </form>

    <p><a href="?page=requests">Back to list</a></p>
</div>
</body>
</html>

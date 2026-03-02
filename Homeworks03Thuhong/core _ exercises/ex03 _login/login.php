<?php
session_start();

/* Hardcoded credentials */
$correctUser = "admin";
$correctPass = "123456";

/* Initialize attempt counter */
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

$message = "";
$messageClass = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $correctUser && $password === $correctPass) {
        $message = "Login Successful";
        $messageClass = "success";
        $_SESSION['attempts'] = 0; // reset attempts on success
    } else {
        $_SESSION['attempts']++;
        $message = "Invalid Credentials";
        $messageClass = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-box {
            background: #fff;
            width: 360px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #5a67d8;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .attempts {
            text-align: center;
            margin-top: 8px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    <form method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>

        <button type="submit">Login</button>
    </form>

    <?php if ($message !== ""): ?>
        <div class="message <?= $messageClass ?>">
            <?= $message ?>
        </div>

        <?php if ($messageClass === "error"): ?>
            <div class="attempts">
                Failed Attempts: <?= $_SESSION['attempts'] ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>

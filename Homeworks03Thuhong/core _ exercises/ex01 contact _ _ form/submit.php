<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .result-box {
            background: #fff;
            padding: 25px;
            width: 420px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="result-box">
<?php
$fullname = trim($_POST['fullname'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$message  = trim($_POST['message'] ?? '');

if ($fullname === '' || $email === '' || $phone === '' || $message === '') {
    echo "<p class='error'>Missing Data</p>";
} else {
    echo "<h2>Submitted Information</h2>";
    echo "<ul>";
    echo "<li><strong>Full Name:</strong> $fullname</li>";
    echo "<li><strong>Email:</strong> $email</li>";
    echo "<li><strong>Phone:</strong> $phone</li>";
    echo "<li><strong>Message:</strong> $message</li>";
    echo "</ul>";
}
?>
</div>

</body>
</html>

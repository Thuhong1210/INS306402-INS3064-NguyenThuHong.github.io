<?php
$fullName    = "Nguyễn Thu Hồng";
$dateOfBirth = "12/10/2003";
$hometown    = "Vĩnh Phúc";
$hobbies     = "Reading books, listening to music, traveling";

$accessTime = date("d/m/Y - H:i:s");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Personal Info</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #fbc2eb, #a6c1ee);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-box {
            width: 420px;
            background: #ffffff;
            padding: 28px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            color: #6f42c1;
            margin-bottom: 22px;
        }

        .row {
            margin-bottom: 12px;
            padding: 10px 14px;
            background: #f3e8ff;
            border-radius: 10px;
            font-size: 15px;
            color: #333;
        }

        .row strong {
            color: #5a189a;
        }

        .time {
            margin-top: 18px;
            text-align: center;
            font-size: 13px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="profile-box">
    <h2>Personal Information</h2>

    <div class="row">
        <strong>Full Name:</strong>
        <?php echo htmlspecialchars($fullName); ?>
    </div>

    <div class="row">
        <strong>Date of Birth:</strong>
        <?php echo htmlspecialchars($dateOfBirth); ?>
    </div>

    <div class="row">
        <strong>Hometown:</strong>
        <?php echo htmlspecialchars($hometown); ?>
    </div>

    <div class="row">
        <strong>Hobbies:</strong>
        <?php echo htmlspecialchars($hobbies); ?>
    </div>

    <div class="time">
        Access Date & Time: <?php echo $accessTime; ?>
    </div>
</div>

</body>
</html>

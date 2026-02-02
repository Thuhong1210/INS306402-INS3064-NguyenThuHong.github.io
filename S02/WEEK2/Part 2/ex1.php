<?php
// ex1.php - classify a score (0-100) using if/elseif/else
$score = isset($_GET['score']) ? $_GET['score'] : null;

if ($score === null) {
    echo "Usage: ?score=85";
    exit;
}

if (!is_numeric($score) || $score < 0 || $score > 100) {
    echo "Invalid score. Provide a number between 0 and 100.";
    exit;
}

$score = (int)$score;

if ($score >= 90) {
    $grade = 'A';
} elseif ($score >= 80) {
    $grade = 'B';
} elseif ($score >= 70) {
    $grade = 'C';
} else {
    $grade = 'F';
}

echo "Score: {$score} -> Grade: {$grade}";
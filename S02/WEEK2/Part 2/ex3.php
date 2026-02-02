<?php
// ex3.php - 5x5 multiplication grid using nested for-loops
header('Content-Type: text/plain');

for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= 5; $j++) {
        echo $i * $j;
        if ($j < 5) echo ' ';
    }
    echo PHP_EOL;
}
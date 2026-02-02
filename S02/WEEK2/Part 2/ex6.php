<?php
// ex6.php - print even numbers from 1 to 20 using a for loop and modulo check

$evens = [];
for ($i = 1; $i <= 20; $i++) {
    if ($i % 2 == 0) {
        $evens[] = $i;
    }
}

echo implode(', ', $evens);
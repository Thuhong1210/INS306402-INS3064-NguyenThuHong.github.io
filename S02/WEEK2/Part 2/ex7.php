<?php
$input = [10, 20, 30, 40, 50]; // five numbers

$reversed = [];
for ($i = count($input) - 1; $i >= 0; $i--) {
    $reversed[] = $input[$i];
}

echo '[' . implode(', ', $reversed) . ']'; // Output: [50, 40, 30, 20, 10]
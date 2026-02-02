<?php
declare(strict_types=1);

function isAdult(?int $age): bool {
    return $age !== null && $age >= 18;
}

// Example usage matching example_output.txt
$input = isAdult(null);
// Output: False
echo $input ? 'True' : 'False';
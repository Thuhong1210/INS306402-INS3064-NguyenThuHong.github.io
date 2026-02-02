<?php
declare(strict_types=1);

function safeDiv(float $a, float $b): ?float {
    if ($b == 0.0) {
        return null;
    }
    return $a / $b;
}

// Examples
echo json_encode(safeDiv(10, 0)) . PHP_EOL; // null
echo json_encode(safeDiv(10, 2)) . PHP_EOL; // 5
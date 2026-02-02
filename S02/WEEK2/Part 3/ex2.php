<?php
declare(strict_types=1);

function area(float $w, float $h): float {
    return $w * $h;
}

$input = area(5.5, 2);
// Output: 11.0
printf("%.1f", $input);
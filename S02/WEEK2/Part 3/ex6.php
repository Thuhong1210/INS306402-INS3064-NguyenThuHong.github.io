<?php
declare(strict_types=1);

/**
 * Adds two integers and returns their sum.
 *
 * example_output.txt
 * // Output: Sum of inputs
 */
function add(int $a, int $b): int
{
    return $a + $b;
}

// Example usage:
$input = add(5, 10);
echo $input; // Output: 15

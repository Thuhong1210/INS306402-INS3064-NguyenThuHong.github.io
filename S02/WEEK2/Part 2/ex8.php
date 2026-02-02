<?php
$result = [];
for ($i = 1; $i <= 50; $i++) {
    if ($i % 15 === 0) {
        $result[] = 'FizzBuzz';
    } elseif ($i % 3 === 0) {
        $result[] = 'Fizz';
    } elseif ($i % 5 === 0) {
        $result[] = 'Buzz';
    } else {
        $result[] = (string)$i;
    }
}
echo implode(', ', $result);
<?php
$i = 10;
while ($i >= 1) {
    echo $i;
    if ($i > 1) {
        echo ', ';
    } else {
        echo ', Liftoff!';
    }
    $i--;
}
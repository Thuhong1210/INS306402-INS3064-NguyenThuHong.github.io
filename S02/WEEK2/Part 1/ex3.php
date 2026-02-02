<?php
$str = "25.50";

// Cast types
$floatVal = (float) $str;
$intVal   = (int) $floatVal;

// Print type and value
echo gettype($floatVal) . "($floatVal), ";
echo gettype($intVal) . "($intVal)";
?>

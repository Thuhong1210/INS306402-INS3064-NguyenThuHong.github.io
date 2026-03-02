<?php
require_once 'utils.php';

echo "<h2>Validation Library Test</h2>";

$name = "  John Doe  ";
$email = "john@example.com";
$age = "25";

echo "<h3>Sanitization</h3>";
echo "Sanitized Name: " . sanitize_string($name) . "<br>";
echo "Sanitized Email: " . sanitize_email($email) . "<br>";
echo "Sanitized Age: " . sanitize_int($age) . "<br>";

echo "<h3>Validation</h3>";

echo "Name required: " . (is_required($name) ? "✔️ Valid" : "❌ Invalid") . "<br>";
echo "Email valid: " . (is_valid_email($email) ? "✔️ Valid" : "❌ Invalid") . "<br>";
echo "Min length (3): " . (min_length($name, 3) ? "✔️ Valid" : "❌ Invalid") . "<br>";
echo "Max length (20): " . (max_length($name, 20) ? "✔️ Valid" : "❌ Invalid") . "<br>";
echo "Age in range (18-60): " . (is_in_range((int)$age, 18, 60) ? "✔️ Valid" : "❌ Invalid") . "<br>";

echo "<h3>Regex Example</h3>";
$username = "user_123";
$pattern = "/^[a-zA-Z0-9_]+$/";

echo "Username valid (letters, numbers, underscore): "
    . (matches_pattern($username, $pattern) ? "✔️ Valid" : "❌ Invalid");
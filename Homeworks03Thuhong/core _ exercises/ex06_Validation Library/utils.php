<?php
/**
 * utils.php
 * Reusable helper functions for sanitization & validation
 */

/* =========================
   SANITIZATION FUNCTIONS
========================= */

/**
 * Sanitize string input
 */
function sanitize_string(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize email
 */
function sanitize_email(string $email): string {
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

/**
 * Sanitize integer
 */
function sanitize_int($number): int {
    return filter_var($number, FILTER_SANITIZE_NUMBER_INT);
}


/* =========================
   VALIDATION FUNCTIONS
========================= */

/**
 * Check required field
 */
function is_required(string $value): bool {
    return trim($value) !== '';
}

/**
 * Validate email format
 */
function is_valid_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate minimum length
 */
function min_length(string $value, int $min): bool {
    return strlen(trim($value)) >= $min;
}

/**
 * Validate maximum length
 */
function max_length(string $value, int $max): bool {
    return strlen(trim($value)) <= $max;
}

/**
 * Validate integer range
 */
function is_in_range(int $number, int $min, int $max): bool {
    return $number >= $min && $number <= $max;
}

/**
 * Validate using regex pattern
 */
function matches_pattern(string $value, string $pattern): bool {
    return preg_match($pattern, $value) === 1;
}
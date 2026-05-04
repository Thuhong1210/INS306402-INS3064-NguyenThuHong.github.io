<?php

class RequestValidator
{
    public function validateCreate(array $data): array
    {
        $errors = [];

        if (empty(trim($data['title'] ?? ''))) {
            $errors[] = 'Title is required.';
        }
        if (empty(trim($data['description'] ?? ''))) {
            $errors[] = 'Description is required.';
        }
        if (empty(trim($data['room'] ?? ''))) {
            $errors[] = 'Room is required.';
        }

        return $errors;
    }

    public function isValidStatus(string $status): bool
    {
        $allowedStatuses = ['Pending', 'In Progress', 'Done'];
        return in_array($status, $allowedStatuses, true);
    }
}

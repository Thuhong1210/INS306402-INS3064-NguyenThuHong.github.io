<?php

class RequestRepository
{
    public function __construct()
    {
        if (!isset($_SESSION['requests'])) {
            $_SESSION['requests'] = [
                1 => [
                    'id' => 1,
                    'title' => 'Projector not working',
                    'description' => 'The projector in Room A201 cannot connect to the laptop.',
                    'room' => 'A201',
                    'status' => 'Pending',
                    'createdBy' => 'Student',
                ],
                2 => [
                    'id' => 2,
                    'title' => 'Need access to MIS lab',
                    'description' => 'I need access to the MIS lab for group project practice.',
                    'room' => 'MIS Lab',
                    'status' => 'In Progress',
                    'createdBy' => 'Student',
                ],
            ];
        }
    }

    public function all(): array
    {
        $requests = [];
        foreach ($_SESSION['requests'] as $item) {
            $requests[] = $this->mapToRequest($item);
        }
        return $requests;
    }

    public function find(int $id): ?Request
    {
        if (!isset($_SESSION['requests'][$id])) {
            return null;
        }
        return $this->mapToRequest($_SESSION['requests'][$id]);
    }

    public function create(string $title, string $description, string $room): Request
    {
        $id = empty($_SESSION['requests']) ? 1 : max(array_keys($_SESSION['requests'])) + 1;
        $_SESSION['requests'][$id] = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'room' => $room,
            'status' => 'Pending',
            'createdBy' => 'Student',
        ];
        return $this->mapToRequest($_SESSION['requests'][$id]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        if (!isset($_SESSION['requests'][$id])) {
            return false;
        }
        $_SESSION['requests'][$id]['status'] = $status;
        return true;
    }

    private function mapToRequest(array $data): Request
    {
        return new Request(
            (int) $data['id'],
            (string) $data['title'],
            (string) $data['description'],
            (string) $data['room'],
            (string) $data['status'],
            (string) $data['createdBy']
        );
    }
}

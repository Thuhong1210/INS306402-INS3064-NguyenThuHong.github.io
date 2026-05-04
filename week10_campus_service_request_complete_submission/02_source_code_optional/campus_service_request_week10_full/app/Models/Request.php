<?php

class Request
{
    public function __construct(
        private int $id,
        private string $title,
        private string $description,
        private string $room,
        private string $status = 'Pending',
        private string $createdBy = 'Student'
    ) {}

    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getRoom(): string { return $this->room; }
    public function getStatus(): string { return $this->status; }
    public function getCreatedBy(): string { return $this->createdBy; }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}

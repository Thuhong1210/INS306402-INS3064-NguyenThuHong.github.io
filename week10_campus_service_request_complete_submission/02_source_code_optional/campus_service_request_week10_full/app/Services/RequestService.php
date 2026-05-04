<?php

class RequestService
{
    public function __construct(
        private RequestRepository $requestRepository,
        private RequestValidator $requestValidator
    ) {}

    public function getAllRequests(): array
    {
        return $this->requestRepository->all();
    }

    public function getRequestById(int $id): ?Request
    {
        return $this->requestRepository->find($id);
    }

    public function submitRequest(array $data): array
    {
        $errors = $this->requestValidator->validateCreate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $request = $this->requestRepository->create(
            trim($data['title']),
            trim($data['description']),
            trim($data['room'])
        );

        return ['success' => true, 'request' => $request];
    }

    public function changeStatus(int $id, string $newStatus): bool
    {
        if (!$this->requestValidator->isValidStatus($newStatus)) {
            return false;
        }
        return $this->requestRepository->updateStatus($id, $newStatus);
    }
}

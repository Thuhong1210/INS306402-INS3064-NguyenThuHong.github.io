<?php

class RequestController
{
    public function __construct(
        private RequestService $requestService,
        private ViewRenderer $viewRenderer
    ) {}

    public function index(): void
    {
        $requests = $this->requestService->getAllRequests();
        $this->viewRenderer->render('requests/index', ['requests' => $requests, 'title' => 'All Requests']);
    }

    public function staffIndex(): void
    {
        $requests = $this->requestService->getAllRequests();
        $this->viewRenderer->render('requests/index', ['requests' => $requests, 'title' => 'Staff Request Dashboard']);
    }

    public function create(): void
    {
        $this->viewRenderer->render('requests/create', ['errors' => []]);
    }

    public function store(): void
    {
        $result = $this->requestService->submitRequest($_POST);
        if ($result['success'] === false) {
            $this->viewRenderer->render('requests/create', ['errors' => $result['errors']]);
            return;
        }

        header('Location: ?page=requests');
        exit;
    }

    public function show(int $id): void
    {
        $request = $this->requestService->getRequestById($id);
        if ($request === null) {
            http_response_code(404);
            echo 'Request not found.';
            return;
        }
        $this->viewRenderer->render('requests/show', ['request' => $request]);
    }

    public function updateStatus(int $id): void
    {
        $newStatus = $_POST['status'] ?? 'Pending';
        $this->requestService->changeStatus($id, $newStatus);

        header('Location: ?page=show&id=' . $id);
        exit;
    }
}

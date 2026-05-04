<?php
session_start();

require_once __DIR__ . '/../app/Models/Request.php';
require_once __DIR__ . '/../app/Models/RequestRepository.php';
require_once __DIR__ . '/../app/Validators/RequestValidator.php';
require_once __DIR__ . '/../app/Services/RequestService.php';
require_once __DIR__ . '/../app/Core/ViewRenderer.php';
require_once __DIR__ . '/../app/Controllers/RequestController.php';

$requestRepository = new RequestRepository();
$requestValidator = new RequestValidator();
$requestService = new RequestService($requestRepository, $requestValidator);
$viewRenderer = new ViewRenderer();
$requestController = new RequestController($requestService, $viewRenderer);

$page = $_GET['page'] ?? 'requests';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

switch ($page) {
    case 'requests':
        $requestController->index();
        break;

    case 'staff-requests':
        $requestController->staffIndex();
        break;

    case 'create':
        $requestController->create();
        break;

    case 'store':
        $requestController->store();
        break;

    case 'show':
        $requestController->show($id);
        break;

    case 'update-status':
        $requestController->updateStatus($id);
        break;

    default:
        http_response_code(404);
        echo 'Page not found.';
}

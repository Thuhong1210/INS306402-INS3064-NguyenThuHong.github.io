<?php

class ViewRenderer
{
    public function render(string $viewPath, array $data = []): void
    {
        extract($data);
        $fullPath = __DIR__ . '/../Views/' . $viewPath . '.php';

        if (!file_exists($fullPath)) {
            echo 'View not found: ' . htmlspecialchars($viewPath);
            return;
        }

        require $fullPath;
    }
}

<?php

namespace Core;

class BaseController
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        $contentView = base_path('views/' . $view . '.php');
        $layoutView = base_path('views/layouts/' . $layout . '.php');

        if (!file_exists($contentView)) {
            die('View not found: ' . $view);
        }

        require $layoutView;
    }

    protected function jsonSuccess(string $message, array $data = [], int $status = 200): void
    {
        $this->response->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function jsonError(string $message, array $data = [], int $status = 422): void
    {
        $this->response->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}

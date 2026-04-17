<?php

use Core\BaseController;
use Services\AdminService;

class AdminController extends BaseController
{
    public function dashboard(): void
    {
        $service = new AdminService();
        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $service->dashboard(),
        ], 'admin');
    }
}

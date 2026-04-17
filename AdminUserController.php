<?php

use Core\BaseController;
use Services\RoleService;
use Services\UserService;

class AdminUserController extends BaseController
{
    public function index(): void
    {
        $service = new UserService();
        $this->view('admin/users/index', [
            'title' => 'Manage Users',
            'users' => $service->listForAdmin((string) $this->request->input('keyword', '')),
        ], 'admin');
    }

    public function updateRole(string $id): void
    {
        $service = new RoleService();
        $roles = $this->request->input('roles', []);
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        $service->syncRoles((int) $id, $roles);
        flash('success', 'Cập nhật quyền thành công.');
        $this->response->redirect('admin/users');
    }

    public function updateStatus(string $id): void
    {
        $service = new UserService();
        $service->updateStatus((int) $id, (string) $this->request->input('status', 'active'));
        flash('success', 'Cập nhật trạng thái user thành công.');
        $this->response->redirect('admin/users');
    }
}

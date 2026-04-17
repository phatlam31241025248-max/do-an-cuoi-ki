<?php

use Core\BaseController;
use Helpers\Csrf;
use Helpers\Str;
use Models\CategoryModel;

class AdminCategoryController extends BaseController
{
    private CategoryModel $categories;

    public function __construct()
    {
        parent::__construct();
        $this->categories = new CategoryModel();
    }

    public function index(): void
    {
        $this->view('admin/categories/index', [
            'title' => 'Manage Categories',
            'categories' => $this->categories->all('created_at DESC'),
        ], 'admin');
    }

    public function store(): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            flash('error', 'CSRF token không hợp lệ.');
            $this->response->redirect('admin/categories');
        }

        $this->categories->create([
            'name' => trim($this->request->input('name')),
            'slug' => Str::slug((string) $this->request->input('name')),
            'description' => trim((string) $this->request->input('description')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        flash('success', 'Tạo category thành công.');
        $this->response->redirect('admin/categories');
    }

    public function update(string $id): void
    {
        $this->categories->updateById((int) $id, [
            'name' => trim((string) $this->request->input('name')),
            'slug' => Str::slug((string) $this->request->input('name')),
            'description' => trim((string) $this->request->input('description')),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        flash('success', 'Cập nhật category thành công.');
        $this->response->redirect('admin/categories');
    }

    public function destroy(string $id): void
    {
        $this->categories->deleteById((int) $id);
        flash('success', 'Đã xóa category.');
        $this->response->redirect('admin/categories');
    }
}

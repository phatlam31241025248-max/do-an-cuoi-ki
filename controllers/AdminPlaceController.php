<?php

use Core\BaseController;
use Helpers\Csrf;
use Services\PlaceService;

class AdminPlaceController extends BaseController
{
    private PlaceService $places;

    public function __construct()
    {
        parent::__construct();
        $this->places = new PlaceService();
    }

    public function index(): void
    {
        $this->view('admin/places/index', [
            'title' => 'Manage Places',
            'places' => $this->places->listForAdmin(),
            'categories' => $this->places->getCategories(),
        ], 'admin');
    }

    public function store(): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            flash('error', 'CSRF token không hợp lệ.');
            $this->response->redirect('admin/places');
        }

        $result = $this->places->save($this->request->all(), (int) current_user()['id'], null, [
            'thumbnail' => $this->request->file('thumbnail'),
            'cover_image' => $this->request->file('cover_image'),
        ]);
        flash($result['success'] ? 'success' : 'error', $result['success'] ? 'Tạo place thành công.' : 'Không thể tạo place.');
        $this->response->redirect('admin/places');
    }

    public function update(string $id): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            flash('error', 'CSRF token không hợp lệ.');
            $this->response->redirect('admin/places');
        }

        $result = $this->places->save($this->request->all(), (int) current_user()['id'], (int) $id, [
            'thumbnail' => $this->request->file('thumbnail'),
            'cover_image' => $this->request->file('cover_image'),
        ]);
        flash($result['success'] ? 'success' : 'error', $result['success'] ? 'Cập nhật place thành công.' : 'Không thể cập nhật place.');
        $this->response->redirect('admin/places');
    }

    public function destroy(string $id): void
    {
        $ok = $this->places->delete((int) $id);
        flash($ok ? 'success' : 'error', $ok ? 'Đã xóa place.' : 'Không thể xóa place.');
        $this->response->redirect('admin/places');
    }
}

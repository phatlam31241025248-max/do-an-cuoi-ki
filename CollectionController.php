<?php

use Core\BaseController;
use Helpers\Csrf;
use Services\CollectionService;

class CollectionController extends BaseController
{
    private CollectionService $collections;

    public function __construct()
    {
        parent::__construct();
        $this->collections = new CollectionService();
    }

    public function index(): void
    {
        $this->view('user/collections', [
            'title' => 'My Collections',
            'collections' => $this->collections->getUserCollections((int) current_user()['id']),
        ]);
    }

    public function store(): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            flash('error', 'CSRF token không hợp lệ.');
            $this->response->redirect('collections');
        }

        $result = $this->collections->create((int) current_user()['id'], $this->request->all());
        flash($result['success'] ? 'success' : 'error', $result['success'] ? 'Tạo collection thành công.' : 'Không thể tạo collection.');
        $this->response->redirect('collections');
    }

    public function update(string $id): void
    {
        $result = $this->collections->update((int) $id, (int) current_user()['id'], $this->request->all());
        flash($result['success'] ? 'success' : 'error', $result['success'] ? 'Cập nhật collection thành công.' : 'Không thể cập nhật collection.');
        $this->response->redirect('collections');
    }

    public function destroy(string $id): void
    {
        $ok = $this->collections->delete((int) $id, (int) current_user()['id']);
        flash($ok ? 'success' : 'error', $ok ? 'Đã xóa collection.' : 'Không thể xóa collection.');
        $this->response->redirect('collections');
    }

    public function togglePlace(string $id): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            $this->jsonError('CSRF token không hợp lệ.', [], 419);
        }

        $result = $this->collections->togglePlace((int) $id, (int) $this->request->input('place_id'), (int) current_user()['id']);
        if (!$result['success']) {
            $this->jsonError($result['message']);
        }

        $this->jsonSuccess($result['in_collection'] ? 'Đã thêm địa điểm vào collection.' : 'Đã gỡ địa điểm khỏi collection.', [
            'in_collection' => $result['in_collection'],
        ]);
    }
}

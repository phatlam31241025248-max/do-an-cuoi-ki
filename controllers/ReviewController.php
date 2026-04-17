<?php

use Core\BaseController;
use Helpers\Csrf;
use Services\PlaceService;
use Services\ReviewService;

class ReviewController extends BaseController
{
    private ReviewService $reviews;
    private PlaceService $places;

    public function __construct()
    {
        parent::__construct();
        $this->reviews = new ReviewService();
        $this->places = new PlaceService();
    }

    public function createPage(): void
    {
        $this->view('reviews/studio', [
            'title' => 'Review Studio',
            'places' => $this->places->getPlaceOptions(),
            'categories' => $this->places->getCategories(),
        ]);
    }

    public function storeStudio(): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            flash('error', 'CSRF token không hợp lệ.');
            $this->response->redirect('review-studio');
        }

        $result = $this->reviews->createFromStudio((int) current_user()['id'], $this->request->all(), [
            'image' => $this->request->file('image'),
            'new_thumbnail' => $this->request->file('new_thumbnail'),
            'new_cover_image' => $this->request->file('new_cover_image'),
        ]);
        if (!$result['success']) {
            set_old($this->request->all());
            flash('error', implode(' ', array_merge(...array_values($result['errors']))));
            $this->response->redirect('review-studio');
        }

        clear_old();
        $message = !empty($result['created_new_place'])
            ? 'Bạn đã tạo địa điểm mới và đăng review đầu tiên thành công.'
            : 'Review đã được đăng thành công.';
        flash('success', $message);
        $this->response->redirect('places/' . $result['place_slug']);
    }

    public function store(): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            flash('error', 'CSRF token không hợp lệ.');
            $this->response->redirect('places');
        }

        $userId = (int) current_user()['id'];
        $result = $this->reviews->create($userId, $this->request->all(), $this->request->file('image'));

        if (!$result['success']) {
            flash('error', implode(' ', array_merge(...array_values($result['errors']))));
            $this->response->redirect('places/' . $this->request->input('place_slug'));
        }

        flash('success', 'Review đã được đăng.');
        $this->response->redirect('places/' . $this->request->input('place_slug'));
    }

    public function update(string $id): void
    {
        $result = $this->reviews->update((int) $id, (int) current_user()['id'], $this->request->all(), $this->request->file('image'));
        if (!$result['success']) {
            flash('error', implode(' ', array_merge(...array_values($result['errors']))));
        } else {
            flash('success', 'Cập nhật review thành công.');
        }
        $this->response->redirect($_SERVER['HTTP_REFERER'] ?? 'my-reviews');
    }

    public function destroy(string $id): void
    {
        $ok = $this->reviews->delete((int) $id, (int) current_user()['id']);
        flash($ok ? 'success' : 'error', $ok ? 'Đã xóa review.' : 'Không thể xóa review.');
        $this->response->redirect($_SERVER['HTTP_REFERER'] ?? 'my-reviews');
    }

    public function like(string $id): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            $this->jsonError('CSRF token không hợp lệ.', [], 419);
        }

        $result = $this->reviews->toggleLike((int) $id, (int) current_user()['id']);
        if (!$result['success']) {
            $this->jsonError($result['message'] ?? 'Không thể like review.');
        }

        $this->jsonSuccess($result['liked'] ? 'Đã like review.' : 'Đã bỏ like review.', [
            'liked' => $result['liked'],
            'helpful_count' => $result['helpful_count'],
        ]);
    }
}

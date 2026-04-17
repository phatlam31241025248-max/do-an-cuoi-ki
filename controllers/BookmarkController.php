<?php

use Core\BaseController;
use Helpers\Csrf;
use Services\BookmarkService;

class BookmarkController extends BaseController
{
    public function toggle(string $id): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            $this->jsonError('CSRF token không hợp lệ.', [], 419);
        }

        $service = new BookmarkService();
        $result = $service->toggle((int) current_user()['id'], (int) $id);
        $this->jsonSuccess($result['bookmarked'] ? 'Đã lưu địa điểm.' : 'Đã bỏ lưu địa điểm.', [
            'bookmarked' => $result['bookmarked'],
        ]);
    }
}

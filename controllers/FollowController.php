<?php

use Core\BaseController;
use Helpers\Csrf;
use Services\FollowService;

class FollowController extends BaseController
{
    public function toggle(string $id): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            $this->jsonError('CSRF token không hợp lệ.', [], 419);
        }

        $service = new FollowService();
        $result = $service->toggle((int) current_user()['id'], (int) $id);
        if (!$result['success']) {
            $this->jsonError($result['message']);
        }

        $this->jsonSuccess($result['following'] ? 'Đã follow user.' : 'Đã unfollow user.', [
            'following' => $result['following'],
            'followers_count' => $result['followers_count'],
            'following_count' => $result['following_count'],
        ]);
    }
}

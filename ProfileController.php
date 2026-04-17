<?php

use Core\BaseController;
use Helpers\Csrf;
use Services\BookmarkService;
use Services\FollowService;
use Services\ReviewService;
use Services\UserService;

class ProfileController extends BaseController
{
    public function show(string $username): void
    {
        $userService = new UserService();
        $followService = new FollowService();
        $profile = $userService->getProfile($username);

        if (!$profile) {
            http_response_code(404);
            die('User not found');
        }

        $isFollowing = is_logged_in() ? $followService->isFollowing((int) current_user()['id'], (int) $profile['id']) : false;

        $this->view('profile/show', [
            'title' => $profile['full_name'],
            'profile' => $profile,
            'isFollowing' => $isFollowing,
        ]);
    }

    public function edit(): void
    {
        $this->view('profile/edit', [
            'title' => 'Edit Profile',
        ]);
    }

    public function update(): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            flash('error', 'CSRF token không hợp lệ.');
            $this->response->redirect('profile/edit');
        }

        $service = new UserService();
        $result = $service->updateProfile((int) current_user()['id'], $this->request->all());
        flash($result['success'] ? 'success' : 'error', $result['success'] ? 'Cập nhật hồ sơ thành công.' : 'Không thể cập nhật hồ sơ.');
        $this->response->redirect('profile/' . current_user()['username']);
    }

    public function myReviews(): void
    {
        $service = new UserService();
        $profile = $service->getProfile((string) current_user()['username']);
        $this->view('user/feed', [
            'title' => 'My Reviews',
            'feed' => $profile['reviews'] ?? [],
            'feedTitle' => 'My Reviews',
        ]);
    }

    public function bookmarks(): void
    {
        $service = new BookmarkService();
        $this->view('user/bookmarks', [
            'title' => 'My Bookmarks',
            'bookmarks' => $service->getUserBookmarks((int) current_user()['id']),
        ]);
    }

    public function feed(): void
    {
        $service = new ReviewService();
        $this->view('user/feed', [
            'title' => 'Following Feed',
            'feed' => $service->followingFeed((int) current_user()['id']),
            'feedTitle' => 'Following Feed',
        ]);
    }
}

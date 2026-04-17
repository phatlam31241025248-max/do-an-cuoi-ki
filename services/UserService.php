<?php

namespace Services;

use Helpers\Auth;
use Helpers\Validator;
use Models\UserModel;
use Models\ReviewModel;
use Models\CollectionModel;

class UserService
{
    private UserModel $users;
    private ReviewModel $reviews;
    private CollectionModel $collections;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->reviews = new ReviewModel();
        $this->collections = new CollectionModel();
    }

    public function getProfile(string $username): ?array
    {
        $profile = $this->users->getProfileByUsername($username);
        if (!$profile) {
            return null;
        }

        $profile['reviews'] = $this->reviews->getByUser((int) $profile['id']);
        $profile['collections'] = $this->collections->getByUser((int) $profile['id']);

        return $profile;
    }

    public function updateProfile(int $userId, array $data): array
    {
        $errors = Validator::validate($data, [
            'full_name' => ['required', 'min:3', 'max:120'],
            'bio' => ['max:255'],
        ]);

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $this->users->updateById($userId, [
            'full_name' => trim($data['full_name']),
            'bio' => trim($data['bio'] ?? ''),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Auth::refresh();
        return ['success' => true];
    }

    public function getTopReviewers(int $limit = 5): array
    {
        return $this->users->getTopReviewers($limit);
    }

    public function listForAdmin(string $keyword = ''): array
    {
        return $this->users->paginateForAdmin($keyword);
    }

    public function updateStatus(int $userId, string $status): bool
    {
        return $this->users->updateById($userId, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

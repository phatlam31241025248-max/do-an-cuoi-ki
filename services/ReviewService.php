<?php

namespace Services;

use Core\Database;
use Helpers\Upload;
use Helpers\Validator;
use Models\PlaceModel;
use Models\ReviewLikeModel;
use Models\ReviewModel;

class ReviewService
{
    private ReviewModel $reviews;
    private PlaceModel $places;
    private ReviewLikeModel $likes;
    private NotificationService $notifications;
    private PlaceService $placeService;

    public function __construct()
    {
        $this->reviews = new ReviewModel();
        $this->places = new PlaceModel();
        $this->likes = new ReviewLikeModel();
        $this->notifications = new NotificationService();
        $this->placeService = new PlaceService();
    }

    public function homeFeed(): array
    {
        return $this->reviews->getHomeFeed();
    }

    public function followingFeed(int $userId): array
    {
        return $this->reviews->getFollowingFeed($userId);
    }

    public function create(int $userId, array $data, ?array $imageFile = null): array
    {
        $errors = Validator::validate($data, [
            'place_id' => ['required', 'integer'],
            'rating' => ['required', 'integer'],
            'title' => ['required', 'min:3', 'max:150'],
            'content' => ['required', 'min:10', 'max:2000'],
        ]);

        $rating = (int) ($data['rating'] ?? 0);
        if ($rating < 1 || $rating > 5) {
            $errors['rating'][] = 'Rating phải từ 1 đến 5.';
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $imagePath = Upload::storeImage($imageFile, 'reviews');
        } catch (\RuntimeException $exception) {
            return ['success' => false, 'errors' => ['image' => [$exception->getMessage()]]];
        }

        $reviewId = $this->reviews->create([
            'user_id' => $userId,
            'place_id' => (int) $data['place_id'],
            'rating' => $rating,
            'title' => trim($data['title']),
            'content' => trim($data['content']),
            'image' => $imagePath,
            'verified_score' => 60,
            'rank_score' => ($rating * 1.5) + 48,
            'helpful_count' => 0,
            'report_count' => 0,
            'status' => 'visible',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->places->recalculateStats((int) $data['place_id']);
        return ['success' => true, 'id' => $reviewId, 'image' => $imagePath];
    }

    public function createFromStudio(int $userId, array $data, array $files = []): array
    {
        $mode = ($data['place_mode'] ?? 'existing') === 'new' ? 'new' : 'existing';
        $connection = Database::connection();
        $uploadedReviewImage = null;
        $uploadedPlaceFiles = [];

        try {
            $connection->beginTransaction();

            if ($mode === 'new') {
                $placeResult = $this->placeService->createFromUserReview($data, $userId, [
                    'thumbnail' => $files['new_thumbnail'] ?? null,
                    'cover_image' => $files['new_cover_image'] ?? null,
                ]);
                if (!$placeResult['success']) {
                    $connection->rollBack();
                    return $placeResult;
                }
                $data['place_id'] = $placeResult['id'];
                $uploadedPlaceFiles = $placeResult['uploaded_files'] ?? [];
            }

            $reviewResult = $this->create($userId, $data, $files['image'] ?? null);
            if (!$reviewResult['success']) {
                $connection->rollBack();
                foreach ($uploadedPlaceFiles as $filePath) {
                    Upload::delete($filePath);
                }
                return $reviewResult;
            }
            $uploadedReviewImage = $reviewResult['image'] ?? null;

            $place = $this->places->find((int) $data['place_id']);
            $connection->commit();

            return [
                'success' => true,
                'id' => $reviewResult['id'],
                'place_slug' => $place['slug'] ?? '',
                'place_id' => (int) $data['place_id'],
                'created_new_place' => $mode === 'new',
            ];
        } catch (\Throwable $exception) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
            if ($uploadedReviewImage) {
                Upload::delete($uploadedReviewImage);
            }
            foreach ($uploadedPlaceFiles as $filePath) {
                Upload::delete($filePath);
            }

            return [
                'success' => false,
                'errors' => [
                    'system' => ['Không thể đăng review lúc này. Vui lòng thử lại.'],
                ],
            ];
        }
    }

    public function update(int $reviewId, int $userId, array $data, ?array $imageFile = null): array
    {
        $review = $this->reviews->find($reviewId);
        if (!$review || (int) $review['user_id'] !== $userId) {
            return ['success' => false, 'errors' => ['review' => ['Không có quyền sửa review này.']]];
        }

        $errors = Validator::validate($data, [
            'rating' => ['required', 'integer'],
            'title' => ['required', 'min:3', 'max:150'],
            'content' => ['required', 'min:10', 'max:2000'],
        ]);
        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $payload = [
            'rating' => (int) $data['rating'],
            'title' => trim($data['title']),
            'content' => trim($data['content']),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $newImage = null;
        try {
            $newImage = Upload::storeImage($imageFile, 'reviews');
            if ($newImage) {
                $payload['image'] = $newImage;
            }
        } catch (\RuntimeException $exception) {
            return ['success' => false, 'errors' => ['image' => [$exception->getMessage()]]];
        }

        $this->reviews->updateById($reviewId, $payload);
        if ($newImage && !empty($review['image'])) {
            Upload::delete($review['image']);
        }
        $this->reviews->updateRankScore($reviewId);
        $this->places->recalculateStats((int) $review['place_id']);

        return ['success' => true];
    }

    public function delete(int $reviewId, int $userId, bool $forceAdmin = false): bool
    {
        $review = $this->reviews->find($reviewId);
        if (!$review) {
            return false;
        }
        if (!$forceAdmin && (int) $review['user_id'] !== $userId) {
            return false;
        }

        $ok = $this->reviews->deleteById($reviewId);
        if ($ok) {
            if (!empty($review['image'])) {
                Upload::delete($review['image']);
            }
            $this->places->recalculateStats((int) $review['place_id']);
        }
        return $ok;
    }

    public function hide(int $reviewId): bool
    {
        return $this->reviews->updateById($reviewId, [
            'status' => 'hidden',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function toggleLike(int $reviewId, int $userId): array
    {
        $review = $this->reviews->findDetailed($reviewId);
        if (!$review) {
            return ['success' => false, 'message' => 'Review không tồn tại.'];
        }

        $likedBefore = $this->likes->exists($userId, $reviewId);
        $this->likes->toggle($userId, $reviewId);
        $count = $this->likes->countByReview($reviewId);
        $this->reviews->updateById($reviewId, ['helpful_count' => $count]);
        $this->reviews->updateRankScore($reviewId);

        if (!$likedBefore && (int) $review['user_id'] !== $userId) {
            $this->notifications->create(
                (int) $review['user_id'],
                $userId,
                'like_review',
                $reviewId,
                'đã thích review của bạn về ' . $review['place_name']
            );
        }

        return [
            'success' => true,
            'liked' => !$likedBefore,
            'helpful_count' => $count,
        ];
    }

    public function listForAdmin(): array
    {
        return $this->reviews->forAdmin();
    }
}

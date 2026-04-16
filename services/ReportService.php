<?php

namespace Services;

use Helpers\Validator;
use Models\ReviewModel;
use Models\ReviewReportModel;

class ReportService
{
    private ReviewReportModel $reports;
    private ReviewModel $reviews;
    private NotificationService $notifications;

    public function __construct()
    {
        $this->reports = new ReviewReportModel();
        $this->reviews = new ReviewModel();
        $this->notifications = new NotificationService();
    }

    public function reportReview(int $reviewId, int $userId, array $data): array
    {
        $errors = Validator::validate($data, [
            'reason' => ['required', 'min:3', 'max:255'],
        ]);

        $review = $this->reviews->findDetailed($reviewId);
        if (!$review) {
            $errors['review'][] = 'Review không tồn tại.';
        }

        if ($this->reports->exists($userId, $reviewId)) {
            $errors['report'][] = 'Bạn đã report review này trước đó.';
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $id = $this->reports->create([
            'review_id' => $reviewId,
            'user_id' => $userId,
            'reason' => trim($data['reason']),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $newCount = ((int) $review['report_count']) + 1;
        $this->reviews->updateById($reviewId, ['report_count' => $newCount]);
        $this->reviews->updateRankScore($reviewId);

        return ['success' => true, 'id' => $id, 'report_count' => $newCount];
    }

    public function listReports(): array
    {
        return $this->reports->allDetailed();
    }
}

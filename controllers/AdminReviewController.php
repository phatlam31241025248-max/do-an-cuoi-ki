<?php

use Core\BaseController;
use Services\ReportService;
use Services\ReviewService;

class AdminReviewController extends BaseController
{
    public function index(): void
    {
        $reviews = new ReviewService();
        $reports = new ReportService();
        $this->view('admin/reviews/index', [
            'title' => 'Manage Reviews & Reports',
            'reviews' => $reviews->listForAdmin(),
            'reports' => $reports->listReports(),
        ], 'admin');
    }

    public function hide(string $id): void
    {
        $service = new ReviewService();
        $service->hide((int) $id);
        flash('success', 'Review đã được ẩn.');
        $this->response->redirect('admin/reviews');
    }

    public function destroy(string $id): void
    {
        $service = new ReviewService();
        $service->delete((int) $id, (int) current_user()['id'], true);
        flash('success', 'Review đã được xóa.');
        $this->response->redirect('admin/reviews');
    }
}

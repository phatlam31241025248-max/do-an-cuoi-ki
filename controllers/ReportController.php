<?php

use Core\BaseController;
use Helpers\Csrf;
use Services\ReportService;

class ReportController extends BaseController
{
    public function store(string $id): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            $this->jsonError('CSRF token không hợp lệ.', [], 419);
        }

        $service = new ReportService();
        $result = $service->reportReview((int) $id, (int) current_user()['id'], $this->request->all());
        if (!$result['success']) {
            $this->jsonError('Không thể report review.', ['errors' => $result['errors']]);
        }

        $this->jsonSuccess('Đã gửi report thành công.', ['report_count' => $result['report_count']]);
    }
}

<?php

namespace Services;

use Models\CategoryModel;
use Models\PlaceModel;
use Models\ReviewModel;
use Models\ReviewReportModel;
use Models\UserModel;

class AdminService
{
    public function dashboard(): array
    {
        $users = new UserModel();
        $places = new PlaceModel();
        $reviews = new ReviewModel();
        $reports = new ReviewReportModel();
        $categories = new CategoryModel();

        return [
            'user_count' => $users->countAll(),
            'place_count' => $places->countAll(),
            'review_count' => $reviews->countAll(),
            'report_count' => $reports->countAll(),
            'category_count' => $categories->countAll(),
            'latest_reviews' => $reviews->forAdmin(),
        ];
    }
}

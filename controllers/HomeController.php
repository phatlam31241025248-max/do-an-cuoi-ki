<?php

use Core\BaseController;
use Services\CollectionService;
use Services\PlaceService;
use Services\ReviewService;
use Services\UserService;

class HomeController extends BaseController
{
    public function index(): void
    {
        $placeService = new PlaceService();
        $reviewService = new ReviewService();
        $userService = new UserService();
        $collectionService = new CollectionService();

        $this->view('home/index', [
            'feed' => $reviewService->homeFeed(),
            'categories' => $placeService->getCategories(),
            'topReviewers' => $userService->getTopReviewers(),
            'hotPlaces' => $placeService->getHotPlaces(),
            'featuredCollections' => $collectionService->featuredCollections(),
            'title' => 'Home',
        ]);
    }
}

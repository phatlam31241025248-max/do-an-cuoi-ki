<?php

use Core\BaseController;
use Services\CollectionService;
use Services\PlaceService;

class PlaceController extends BaseController
{
    private PlaceService $places;
    private CollectionService $collections;

    public function __construct()
    {
        parent::__construct();
        $this->places = new PlaceService();
        $this->collections = new CollectionService();
    }

    public function index(): void
    {
        $filters = [
            'keyword' => trim((string) $this->request->input('keyword', '')),
            'category' => trim((string) $this->request->input('category', '')),
            'sort' => trim((string) $this->request->input('sort', 'latest')),
            'page' => (int) $this->request->input('page', 1),
        ];

        $result = $this->places->searchPlaces($filters);

        $this->view('places/index', [
            'title' => 'Khám phá địa điểm',
            'places' => $result['items'],
            'pagination' => $result['pagination'],
            'filters' => $filters,
            'categories' => $this->places->getCategories(),
        ]);
    }

    public function show(string $slug): void
    {
        $place = $this->places->getPlaceDetail($slug);
        if (!$place) {
            http_response_code(404);
            die('Place not found');
        }

        $this->view('places/show', [
            'title' => $place['name'],
            'place' => $place,
            'categories' => $this->places->getCategories(),
            'collections' => is_logged_in() ? $this->collections->getUserCollections((int) current_user()['id']) : [],
        ]);
    }
}

<?php

namespace Services;

use Helpers\Pagination;
use Helpers\Str;
use Helpers\Upload;
use Helpers\Validator;
use Models\CategoryModel;
use Models\PlaceModel;
use Models\ReviewModel;

class PlaceService
{
    private PlaceModel $places;
    private ReviewModel $reviews;
    private CategoryModel $categories;

    public function __construct()
    {
        $this->places = new PlaceModel();
        $this->reviews = new ReviewModel();
        $this->categories = new CategoryModel();
    }

    public function searchPlaces(array $filters): array
    {
        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = (int) config('app.items_per_page');
        $result = $this->places->search($filters, $page, $perPage);

        return [
            'items' => $result['items'],
            'pagination' => Pagination::build($result['total'], $page, $perPage),
            'filters' => $filters,
        ];
    }

    public function getPlaceDetail(string $slug): ?array
    {
        $place = $this->places->findBySlug($slug);
        if (!$place) {
            return null;
        }

        $place['reviews'] = $this->reviews->getByPlace((int) $place['id']);
        return $place;
    }

    public function getCategories(): array
    {
        return $this->categories->all('name ASC');
    }

    public function getPlaceOptions(): array
    {
        return $this->places->all('name ASC');
    }

    public function getHotPlaces(int $limit = 5): array
    {
        return $this->places->getHotPlaces($limit);
    }

    public function save(array $data, int $userId, ?int $id = null, array $files = []): array
    {
        $errors = Validator::validate($data, [
            'category_id' => ['required', 'integer'],
            'name' => ['required', 'min:3', 'max:150'],
            'address' => ['required', 'min:5', 'max:255'],
        ]);

        $existing = $id ? $this->places->find($id) : null;
        if ($id && !$existing) {
            return ['success' => false, 'errors' => ['place' => ['Địa điểm không tồn tại.']]];
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $thumbnail = $existing['thumbnail'] ?? config('app.default_place_image');
        $coverImage = $existing['cover_image'] ?? config('app.default_place_image');
        $oldThumbnail = $existing['thumbnail'] ?? null;
        $oldCoverImage = $existing['cover_image'] ?? null;
        $newThumbnail = null;
        $newCoverImage = null;

        try {
            $newThumbnail = Upload::storeImage($files['thumbnail'] ?? null, 'places');
            if ($newThumbnail) {
                $thumbnail = $newThumbnail;
            }

            $newCoverImage = Upload::storeImage($files['cover_image'] ?? null, 'places');
            if ($newCoverImage) {
                $coverImage = $newCoverImage;
            }
        } catch (\RuntimeException $exception) {
            return ['success' => false, 'errors' => ['image' => [$exception->getMessage()]]];
        }

        $payload = [
            'category_id' => (int) $data['category_id'],
            'name' => trim($data['name']),
            'slug' => $this->resolveUniqueSlug((string) $data['name'], $id),
            'address' => trim($data['address']),
            'description' => trim($data['description'] ?? ''),
            'thumbnail' => $thumbnail,
            'cover_image' => $coverImage,
            'phone' => trim($data['phone'] ?? ''),
            'open_hours' => trim($data['open_hours'] ?? ''),
            'price_range' => trim($data['price_range'] ?? ''),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($id) {
            $this->places->updateById($id, $payload);
            if ($newThumbnail && $oldThumbnail && $oldThumbnail !== config('app.default_place_image')) {
                Upload::delete($oldThumbnail);
            }
            if ($newCoverImage && $oldCoverImage && $oldCoverImage !== config('app.default_place_image')) {
                Upload::delete($oldCoverImage);
            }
            return ['success' => true, 'id' => $id, 'slug' => $payload['slug'], 'uploaded_files' => array_values(array_filter([$newThumbnail, $newCoverImage]))];
        }

        $payload['avg_rating'] = 0;
        $payload['review_count'] = 0;
        $payload['created_by'] = $userId;
        $payload['created_at'] = date('Y-m-d H:i:s');
        $newId = $this->places->create($payload);
        return ['success' => true, 'id' => $newId, 'slug' => $payload['slug'], 'uploaded_files' => array_values(array_filter([$newThumbnail, $newCoverImage]))];
    }

    public function createFromUserReview(array $data, int $userId, array $files = []): array
    {
        return $this->save([
            'category_id' => $data['new_category_id'] ?? null,
            'name' => $data['new_place_name'] ?? '',
            'address' => $data['new_address'] ?? '',
            'description' => $data['new_description'] ?? '',
            'phone' => $data['new_phone'] ?? '',
            'open_hours' => $data['new_open_hours'] ?? '',
            'price_range' => $data['new_price_range'] ?? '',
        ], $userId, null, $files);
    }

    public function delete(int $id): bool
    {
        return $this->places->deleteById($id);
    }

    public function listForAdmin(): array
    {
        return $this->places->allWithCategory();
    }

    private function resolveUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'place';
        $slug = $baseSlug;
        $suffix = 1;

        while ($existing = $this->places->findBySlug($slug)) {
            if ($ignoreId && (int) $existing['id'] === $ignoreId) {
                return $slug;
            }
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }
}

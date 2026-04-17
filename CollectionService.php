<?php

namespace Services;

use Helpers\Validator;
use Models\CollectionModel;
use Models\CollectionPlaceModel;

class CollectionService
{
    private CollectionModel $collections;
    private CollectionPlaceModel $collectionPlaces;

    public function __construct()
    {
        $this->collections = new CollectionModel();
        $this->collectionPlaces = new CollectionPlaceModel();
    }

    public function getUserCollections(int $userId): array
    {
        return $this->collections->getByUser($userId);
    }

    public function featuredCollections(int $limit = 4): array
    {
        return $this->collections->featuredPublic($limit);
    }

    public function create(int $userId, array $data): array
    {
        $errors = Validator::validate($data, [
            'name' => ['required', 'min:3', 'max:100'],
            'description' => ['max:255'],
        ]);

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $id = $this->collections->create([
            'user_id' => $userId,
            'name' => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'privacy' => in_array(($data['privacy'] ?? 'public'), ['public', 'private'], true) ? $data['privacy'] : 'public',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return ['success' => true, 'id' => $id];
    }

    public function update(int $collectionId, int $userId, array $data): array
    {
        $collection = $this->collections->findOwned($collectionId, $userId);
        if (!$collection) {
            return ['success' => false, 'errors' => ['collection' => ['Không có quyền sửa collection này.']]];
        }

        $this->collections->updateById($collectionId, [
            'name' => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'privacy' => in_array(($data['privacy'] ?? 'public'), ['public', 'private'], true) ? $data['privacy'] : 'public',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return ['success' => true];
    }

    public function delete(int $collectionId, int $userId): bool
    {
        $collection = $this->collections->findOwned($collectionId, $userId);
        if (!$collection) {
            return false;
        }
        return $this->collections->deleteById($collectionId);
    }

    public function togglePlace(int $collectionId, int $placeId, int $userId): array
    {
        $collection = $this->collections->findOwned($collectionId, $userId);
        if (!$collection) {
            return ['success' => false, 'message' => 'Không có quyền chỉnh sửa collection này.'];
        }

        $exists = $this->collectionPlaces->exists($collectionId, $placeId);
        $this->collectionPlaces->toggle($collectionId, $placeId);

        return [
            'success' => true,
            'in_collection' => !$exists,
        ];
    }
}

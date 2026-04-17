<?php

namespace Models;

use Core\BaseModel;

class CollectionPlaceModel extends BaseModel
{
    protected string $table = 'collection_places';
    protected string $primaryKey = 'collection_id';
    protected array $fillable = ['collection_id', 'place_id', 'created_at'];

    public function exists(int $collectionId, int $placeId): bool
    {
        return (bool) $this->fetchBySql('SELECT 1 FROM collection_places WHERE collection_id = :collection_id AND place_id = :place_id LIMIT 1', [
            'collection_id' => $collectionId,
            'place_id' => $placeId,
        ]);
    }

    public function toggle(int $collectionId, int $placeId): bool
    {
        if ($this->exists($collectionId, $placeId)) {
            return $this->executeSql('DELETE FROM collection_places WHERE collection_id = :collection_id AND place_id = :place_id', [
                'collection_id' => $collectionId,
                'place_id' => $placeId,
            ]);
        }

        return $this->executeSql('INSERT INTO collection_places (collection_id, place_id, created_at) VALUES (:collection_id, :place_id, NOW())', [
            'collection_id' => $collectionId,
            'place_id' => $placeId,
        ]);
    }
}

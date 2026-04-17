<?php

namespace Services;

use Models\PlaceBookmarkModel;

class BookmarkService
{
    private PlaceBookmarkModel $bookmarks;

    public function __construct()
    {
        $this->bookmarks = new PlaceBookmarkModel();
    }

    public function toggle(int $userId, int $placeId): array
    {
        $bookmarked = $this->bookmarks->exists($userId, $placeId);
        $this->bookmarks->toggle($userId, $placeId);

        return [
            'success' => true,
            'bookmarked' => !$bookmarked,
        ];
    }

    public function getUserBookmarks(int $userId): array
    {
        return $this->bookmarks->getByUser($userId);
    }
}

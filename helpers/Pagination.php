<?php

namespace Helpers;

class Pagination
{
    public static function build(int $total, int $page, int $perPage): array
    {
        $lastPage = max(1, (int) ceil($total / $perPage));

        return [
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => $lastPage,
            'has_prev' => $page > 1,
            'has_next' => $page < $lastPage,
            'prev_page' => max(1, $page - 1),
            'next_page' => min($lastPage, $page + 1),
        ];
    }
}

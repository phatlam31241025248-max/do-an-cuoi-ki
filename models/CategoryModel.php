<?php

namespace Models;

use Core\BaseModel;

class CategoryModel extends BaseModel
{
    protected string $table = 'categories';
    protected array $fillable = ['name', 'slug', 'description', 'created_at', 'updated_at'];

    public function findBySlug(string $slug): ?array
    {
        return $this->fetchBySql('SELECT * FROM categories WHERE slug = :slug LIMIT 1', ['slug' => $slug]);
    }
}

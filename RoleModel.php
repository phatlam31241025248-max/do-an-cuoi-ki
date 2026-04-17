<?php

namespace Models;

use Core\BaseModel;

class RoleModel extends BaseModel
{
    protected string $table = 'roles';
    protected array $fillable = ['name'];

    public function findByName(string $name): ?array
    {
        return $this->fetchBySql('SELECT * FROM roles WHERE name = :name LIMIT 1', ['name' => $name]);
    }
}

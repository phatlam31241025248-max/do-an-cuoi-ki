<?php

namespace Models;

use Core\BaseModel;

class UserRoleModel extends BaseModel
{
    protected string $table = 'user_roles';
    protected string $primaryKey = 'user_id';
    protected array $fillable = ['user_id', 'role_id'];

    public function getRoleNamesForUser(int $userId): array
    {
        $sql = 'SELECT r.name FROM user_roles ur JOIN roles r ON r.id = ur.role_id WHERE ur.user_id = :user_id';
        return array_column($this->fetchAllBySql($sql, ['user_id' => $userId]), 'name');
    }

    public function userHasRole(int $userId, string $roleName): bool
    {
        $sql = 'SELECT COUNT(*) AS total FROM user_roles ur JOIN roles r ON r.id = ur.role_id WHERE ur.user_id = :user_id AND r.name = :role_name';
        $row = $this->fetchBySql($sql, ['user_id' => $userId, 'role_name' => $roleName]);
        return (int) ($row['total'] ?? 0) > 0;
    }

    public function assignRole(int $userId, int $roleId): bool
    {
        return $this->executeSql('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)', [
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);
    }

    public function syncRoles(int $userId, array $roleIds): bool
    {
        $this->executeSql('DELETE FROM user_roles WHERE user_id = :user_id', ['user_id' => $userId]);
        foreach ($roleIds as $roleId) {
            $this->assignRole($userId, (int) $roleId);
        }
        return true;
    }
}

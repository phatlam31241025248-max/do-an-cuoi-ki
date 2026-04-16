<?php

namespace Services;

use Models\RoleModel;
use Models\UserRoleModel;

class RoleService
{
    private RoleModel $roles;
    private UserRoleModel $userRoles;

    public function __construct()
    {
        $this->roles = new RoleModel();
        $this->userRoles = new UserRoleModel();
    }

    public function userHasRole(int $userId, string $roleName): bool
    {
        return $this->userRoles->userHasRole($userId, $roleName);
    }

    public function assignRoleByName(int $userId, string $roleName): bool
    {
        $role = $this->roles->findByName($roleName);
        if (!$role) {
            return false;
        }

        return $this->userRoles->assignRole($userId, (int) $role['id']);
    }

    public function syncRoles(int $userId, array $roleNames): bool
    {
        $roleIds = [];
        foreach ($roleNames as $roleName) {
            $role = $this->roles->findByName($roleName);
            if ($role) {
                $roleIds[] = (int) $role['id'];
            }
        }
        return $this->userRoles->syncRoles($userId, $roleIds);
    }
}

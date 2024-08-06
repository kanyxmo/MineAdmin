<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Service;

use App\Model\Permission\Menu;
use App\Model\Permission\Role;
use App\Model\Permission\User;
use App\Repository\Permission\MenuRepository;
use App\Repository\Permission\RoleRepository;
use App\Service\Permission\UserService;
use Casbin\Enforcer;
use Hyperf\Collection\Collection;

final class PermissionService
{
    public function __construct(
        protected readonly UserService $userService,
        private readonly Enforcer $enforcer,
        private readonly MenuRepository $menuRepository,
        private readonly RoleRepository $roleRepository,
    ) {}

    public function getMenuTreeByUserId(int $userId): Collection
    {
        // 用户本身的菜单 codes
        $userMenuCodes = $this->getEnforce()->getImplicitPermissionsForUser(
            $this->userService->getFieldByUserId($userId, 'username')
        );
        $all = [];
        array_walk_recursive($userMenuCodes, function ($item) use (&$all) {
            $all[] = $item;
        });
        $all = array_unique($all);
        if (! $all) {
            return Collection::make();
        }
        return $this->menuRepository->getMenuByCode(
            $all
        );
    }

    public function getRolesByUserId(int $userId): Collection
    {
        $roleCodes = $this->getEnforce()->getImplicitRolesForUser(
            $this->userService->getFieldByUserId($userId, 'username')
        );
        $all = [];
        array_walk_recursive($roleCodes, function ($item) use (&$all) {
            $all[] = $item;
        });
        $all = array_unique($all);

        if (! $all) {
            return Collection::make();
        }
        return $this->roleRepository->getQuery([
            'code' => $all,
        ])->get();
    }

    public function addUserRole(User $user, Role ...$roles): bool
    {
        return $this->getEnforce()->addRolesForUser($user->username, array_column($roles, 'code'));
    }

    public function hasRole(User $user, Role $role): bool
    {
        return $this->getEnforce()->hasRoleForUser($user->username, $role->code);
    }

    public function removeRole(User $user, Role ...$roles): bool
    {
        return $this->getEnforce()->deleteRolesForUser($user->username, ...array_column($roles, 'code'));
    }

    public function addPermissionRole(Role $role, Menu ...$menu): bool
    {
        return $this->getEnforce()->addPermissionsForUser($role->code, ...array_column($menu, 'code'));
    }

    public function hasPermission(Role $role, Menu $menu): bool
    {
        return $this->getEnforce()->hasPermissionForUser($role->code, $menu->code);
    }

    public function removePermission(Role $role, Menu ...$menu): bool
    {
        return $this->getEnforce()->deletePermissionForUser($role->code, ...array_column($menu, 'code'));
    }

    public function deleteRole(Role $role): bool
    {
        return $this->getEnforce()->deleteRole($role->code);
    }

    public function deletePermission(Menu $menu): bool
    {
        return $this->getEnforce()->deletePermission($menu->code);
    }

    public function deleteUser(User $user): bool
    {
        return $this->getEnforce()->deleteUser($user->username);
    }

    public function getEnforce(): Enforcer
    {
        return $this->enforcer;
    }
}

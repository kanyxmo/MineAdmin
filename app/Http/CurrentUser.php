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

namespace App\Http;

use App\Model\Permission\Menu;
use App\Model\Permission\Role;
use App\Model\Permission\User;
use App\Service\PassportService;
use App\Service\Permission\UserService;
use Hyperf\Collection\Arr;
use Hyperf\Collection\Collection;
use Lcobucci\JWT\Token\RegisteredClaims;
use Mine\Jwt\Traits\RequestScopedTokenTrait;

final class CurrentUser
{
    use RequestScopedTokenTrait;

    public function __construct(
        private readonly PassportService $service,
        private readonly UserService $userService
    ) {}

    public function user(): ?User
    {
        return $this->userService->getInfo($this->id());
    }

    public function refresh(): array
    {
        return $this->service->refreshToken($this->getToken());
    }

    public function id(): int
    {
        return (int) $this->getToken()->claims()->get(RegisteredClaims::ID);
    }

    public function isSuperAdmin(): bool
    {
        return $this->user()->isSuperAdmin();
    }

    public function filterCurrentUser(?array $menuTreeList = null, ?array $permissions = null): array
    {
        $permissions = $permissions ?? $this->user()->getPermissions()->pluck('name')->toArray();
        $menuTreeList = $menuTreeList ?? $this->globalMenuTreeList()->toArray();

        return Arr::where(
            array_map(
                fn(array $menu) => $this->filterMenu($menu, $permissions),
                $menuTreeList
            ),
            fn(array $menu) => in_array($menu['name'], $permissions, true)
        );
    }

    private function filterMenu(array $menu, array $permissions): array
    {
        if (!empty($menu['children'])) {
            $menu['children'] = $this->filterCurrentUser($menu['children'], $permissions);
        }
        return $menu;
    }

    public function globalMenuTreeList(): Collection
    {
        return $this->user()->roles()->get()->map(static function (Role $role) {
            return $role->menus()->where('parent_id', 0)->with('children')->get();
        })->flatten();
    }
}

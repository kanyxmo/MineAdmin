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

namespace HyperfTests\Feature\Admin\Permission;

use App\Http\Common\ResultCode;
use App\Model\Permission\Menu;
use App\Model\Permission\Role;
use Hyperf\Database\Model\ModelNotFoundException;
use Hyperf\Stringable\Str;
use HyperfTests\Feature\Admin\ControllerCase;

/**
 * @internal
 */
final class RoleControllerTest extends ControllerCase
{
    public function testPageList(): void
    {
        $token = $this->token;
        $result = $this->get('/admin/role/list');
        self::assertSame($result['code'], ResultCode::UNAUTHORIZED->value);
        $result = $this->get('/admin/role/list', ['token' => $token]);
        self::assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $enforce = $this->getEnforce();
        self::assertFalse($enforce->hasPermissionForUser($this->user->username, 'permission:role:index'));
        self::assertTrue($enforce->addPermissionForUser($this->user->username, 'permission:role:index'));
        self::assertTrue($enforce->hasPermissionForUser($this->user->username, 'permission:role:index'));
        $result = $this->get('/admin/role/list', ['token' => $token]);
        self::assertSame($result['code'], ResultCode::SUCCESS->value);
        self::assertTrue($enforce->deletePermissionForUser($this->user->username, 'permission:role:index'));
        $result = $this->get('/admin/role/list', ['token' => $token]);
        self::assertSame($result['code'], ResultCode::FORBIDDEN->value);
    }

    public function testCreate(): void
    {
        $token = $this->token;
        $attribute = [
            'name',
            'code',
            'sort',
            'status',
            'remark',
        ];
        $result = $this->post('/admin/role');
        self::assertSame($result['code'], ResultCode::UNPROCESSABLE_ENTITY->value);
        $result = $this->post('/admin/role', [], ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::UNPROCESSABLE_ENTITY->value);
        $fill = [
            'name' => Str::random(10),
            'code' => Str::random(10),
            'sort' => rand(1, 100),
            'status' => rand(1, 2),
            'remark' => Str::random(),
        ];
        $result = $this->post('/admin/role', $fill, ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $enforce = $this->getEnforce();
        self::assertFalse($enforce->hasPermissionForUser($this->user->username, 'permission:role:save'));
        self::assertTrue($enforce->addPermissionForUser($this->user->username, 'permission:role:save'));
        self::assertTrue($enforce->hasPermissionForUser($this->user->username, 'permission:role:save'));
        $result = $this->post('/admin/role', $fill, ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::SUCCESS->value);
        self::assertTrue($enforce->deletePermissionForUser($this->user->username, 'permission:role:save'));
        $result = $this->post('/admin/role', $fill, ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $entity = Role::query()->where('code', $fill['code'])->first();
        self::assertNotNull($entity);
        self::assertSame($entity->name, $fill['name']);
        self::assertSame($entity->sort, $fill['sort']);
        self::assertSame($entity->status, $fill['status']);
        self::assertSame($entity->remark, $fill['remark']);
        $entity->forceDelete();
    }

    public function testSave(): void
    {
        $token = $this->token;
        $entity = Role::create([
            'name' => Str::random(10),
            'code' => Str::random(10),
            'sort' => rand(1, 100),
            'status' => rand(1, 2),
            'remark' => Str::random(),
        ]);
        $result = $this->put('/admin/role/' . $entity->id);
        self::assertSame($result['code'], ResultCode::UNPROCESSABLE_ENTITY->value);
        $result = $this->put('/admin/role/' . $entity->id, [], ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::UNPROCESSABLE_ENTITY->value);
        $fill = [
            'name' => Str::random(10),
            'code' => Str::random(10),
            'sort' => rand(1, 100),
            'status' => rand(1, 2),
            'remark' => Str::random(),
        ];
        $result = $this->put('/admin/role/' . $entity->id, $fill, ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $enforce = $this->getEnforce();
        self::assertFalse($enforce->hasPermissionForUser($this->user->username, 'permission:role:update'));
        self::assertTrue($enforce->addPermissionForUser($this->user->username, 'permission:role:update'));
        self::assertTrue($enforce->hasPermissionForUser($this->user->username, 'permission:role:update'));
        $result = $this->put('/admin/role/' . $entity->id, $fill, ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::SUCCESS->value);
        self::assertTrue($enforce->deletePermissionForUser($this->user->username, 'permission:role:update'));
        $result = $this->put('/admin/role/' . $entity->id, $fill, ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $entity->refresh();
        self::assertSame($entity->name, $fill['name']);
        self::assertSame($entity->sort, $fill['sort']);
        self::assertSame($entity->status, $fill['status']);
        self::assertSame($entity->remark, $fill['remark']);
        $entity->forceDelete();
    }

    public function testDelete(): void
    {
        $token = $this->token;
        $entity = Role::create([
            'name' => Str::random(10),
            'code' => Str::random(10),
            'sort' => rand(1, 100),
            'status' => rand(1, 2),
            'remark' => Str::random(),
        ]);
        $result = $this->delete('/admin/role');
        self::assertSame($result['code'], ResultCode::UNAUTHORIZED->value);
        $result = $this->delete('/admin/role', [], ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $enforce = $this->getEnforce();
        self::assertFalse($enforce->hasPermissionForUser($this->user->username, 'permission:role:delete'));
        self::assertTrue($enforce->addPermissionForUser($this->user->username, 'permission:role:delete'));
        self::assertTrue($enforce->hasPermissionForUser($this->user->username, 'permission:role:delete'));
        $result = $this->delete('/admin/role', [$entity->id], ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::SUCCESS->value);
        self::assertTrue($enforce->deletePermissionForUser($this->user->username, 'permission:role:delete'));
        $result = $this->delete('/admin/role', [$entity->id], ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $this->expectException(ModelNotFoundException::class);
        $entity->refresh();
    }

    public function testBatchGrantPermissionsForRole(): void
    {
        $menus = [
            Menu::create([
                'parent_id' => 0,
                'name' => Str::random(10),

                'icon' => Str::random(10),
                'route' => Str::random(10),
                'component' => Str::random(10),
                'redirect' => Str::random(10),
                'is_hidden' => rand(1, 2),
                'type' => Str::random(1),
                'status' => rand(1, 2),
                'sort' => rand(1, 100),
                'remark' => Str::random(10),
            ]),
            Menu::create([
                'parent_id' => 0,
                'name' => Str::random(10),

                'icon' => Str::random(10),
                'route' => Str::random(10),
                'component' => Str::random(10),
                'redirect' => Str::random(10),
                'is_hidden' => rand(1, 2),
                'type' => Str::random(1),
                'status' => rand(1, 2),
                'sort' => rand(1, 100),
                'remark' => Str::random(10),
            ]),
            Menu::create([
                'parent_id' => 0,
                'name' => Str::random(10),

                'icon' => Str::random(10),
                'route' => Str::random(10),
                'component' => Str::random(10),
                'redirect' => Str::random(10),
                'is_hidden' => rand(1, 2),
                'type' => Str::random(1),
                'status' => rand(1, 2),
                'sort' => rand(1, 100),
                'remark' => Str::random(10),
            ]),
        ];
        $names = array_column($menus, 'name');
        $role = Role::create([
            'name' => Str::random(10),
            'code' => Str::random(10),
            'sort' => rand(1, 100),
            'status' => rand(1, 2),
            'remark' => Str::random(),
        ]);
        $token = $this->token;
        $enforce = $this->getEnforce();
        $uri = '/admin/role/' . $role->id . '/permissions';
        $result = $this->put($uri);
        self::assertSame($result['code'], ResultCode::UNPROCESSABLE_ENTITY->value);
        $result = $this->put($uri, [], ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::UNPROCESSABLE_ENTITY->value);
        $result = $this->put($uri, ['permissions' => $names], ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $userRole = Role::create([
            'name' => Str::random(10),
            'code' => Str::random(10),
            'sort' => rand(1, 100),
            'status' => rand(1, 2),
            'remark' => Str::random(),
        ]);
        self::assertFalse($enforce->hasRoleForUser($this->user->username, $userRole->code));
        self::assertTrue($enforce->addRoleForUser($this->user->username, $userRole->code));
        self::assertTrue($enforce->hasRoleForUser($this->user->username, $userRole->code));

        self::assertFalse($enforce->hasRoleForUser($this->user->username, $role->code));
        self::assertTrue($enforce->addRoleForUser($this->user->username, $role->code));
        self::assertTrue($enforce->hasRoleForUser($this->user->username, $role->code));

        self::assertTrue($enforce->addPermissionForUser($userRole->code, 'permission:set:role'));
        self::assertTrue($enforce->hasPermissionForUser($userRole->code, 'permission:set:role'));

        self::assertTrue($enforce->addPermissionForUser($userRole->code, 'permission:get:role'));
        self::assertTrue($enforce->hasPermissionForUser($userRole->code, 'permission:get:role'));

        $result = $this->put($uri, ['permissions' => $names], ['Authorization' => 'Bearer ' . $token]);
        self::assertSame($result['code'], ResultCode::SUCCESS->value);
        $result = $this->get('/admin/role/' . $role->id . '/permissions', ['token' => $token]);
        self::assertSame($result['code'], ResultCode::SUCCESS->value);
        // 去除 name 空格
        self::assertSame(
            array_map(static fn ($menu) => ['id' => $menu['id'], 'name' => Str::trim($menu['name'])], $result['data']),
            array_map(static fn ($menu) => ['id' => $menu['id'], 'name' => $menu['name']], $menus)
        );

        $enforce->deleteRole($role->code);
        $role->forceDelete();
        Menu::query()->whereIn('name', $names)->forceDelete();
    }
}

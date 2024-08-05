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

namespace App\Http\Admin\Request\Permission\Role;

use Hyperf\Swagger\Annotation\Property;
use Hyperf\Swagger\Annotation\Schema;
use Hyperf\Validation\Request\FormRequest;

#[Schema(
    title: '批量授权角色权限',
    properties: [
        new Property('permission_ids', description: '权限ID', type: 'array', example: '[1,2,3]'),
    ]
)]
class BatchGrantPermissionsForRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'integer|exists:menu,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'permission_ids' => trans('role.permission_ids'),
        ];
    }
}

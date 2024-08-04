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

use App\Schema\RoleSchema;
use Hyperf\Validation\Request\FormRequest;

#[\App\Kernel\Swagger\Attributes\FormRequest(
    schema: RoleSchema::class,
    only: [
        'name', 'code', 'status', 'sort', 'remark',
    ]
)]
class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:60',
            'code' => 'required|string|max:60',
            'status' => 'sometimes|integer|in:0,1',
            'sort' => 'required|integer',
            'remark' => 'nullable|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => trans('role.name'),
            'code' => trans('role.code'),
            'status' => trans('role.status'),
            'sort' => trans('role.sort'),
            'remark' => trans('role.remark'),
        ];
    }
}

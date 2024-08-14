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

namespace App\Events\User;

use App\Model\Permission\User;

/**
 * 登录成功事件.
 */
final class LoginSuccessEvent
{
    public function __construct(
        private readonly User $user
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }
}

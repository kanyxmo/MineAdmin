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

namespace App\Service\Permission;

use App\Repository\Permission\DeptRepository;
use App\Service\IService;

class DeptService extends IService
{
    public function __construct(
        protected readonly DeptRepository $repository
    ) {}
}

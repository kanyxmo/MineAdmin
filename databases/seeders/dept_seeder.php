<?php
/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://gitee.com/xmo/MineAdmin
 */

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */
use App\Model\Permission\Dept;
use Hyperf\Database\Seeders\Seeder;

class SystemDeptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Dept::truncate();
        Dept::create([
            'parent_id' => 0,
            'level' => '0',
            'name' => '曼艺科技',
            'leader' => '曼艺',
            'phone' => '16888888888',
            'created_by' => env('SUPER_ADMIN', 1),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

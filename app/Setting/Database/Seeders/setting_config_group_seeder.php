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
use App\Setting\Model\SettingConfigGroup;
use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class SettingConfigGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Db::table('setting_config_group')->truncate();
        SettingConfigGroup::query()->insert([
            [
                'id' => 1,
                'name' => '站点配置',
                'code' => 'site_config',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2022-07-23 15:08:44',
                'updated_at' => '2022-07-23 15:08:44',
                'remark' => null,
            ],
            [
                'id' => 2,
                'name' => '上传配置',
                'code' => 'upload_config',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2022-07-23 15:09:31',
                'updated_at' => '2022-07-23 15:09:33',
                'remark' => null,
            ],
        ]);
    }
}

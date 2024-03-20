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
use App\Setting\Model\SettingConfigGroup;
use Hyperf\DbConnection\Db;
use Hyperf\Stringable\Str;

beforeEach(function () {
    $this->prefix = '/setting/configGroup';
});
test('config group controller', function () {
    $this->actionTest([
        $this->buildTest('getNoParamsTest') => 'index',
    ]);
    $this->remoteTest();
    Db::transaction(function () {
        testSuccessResponse($this->post($this->prefix . '/save', [
            'name' => Str::random(3),
            'code' => Str::random(4),
        ]));
        $id = SettingConfigGroup::query()->first()->value('id');
        testSuccessResponse($this->delete($this->prefix . '/delete', compact('id')));
    });
});

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

use App\Exception\Handler\AppExceptionHandler;
use App\Exception\Handler\BusinessExceptionHandler;
use App\Exception\Handler\JwtExceptionHandler;
use App\Exception\Handler\ValidationExceptionHandler;
use Hyperf\Database\Model\ModelNotFoundException;
use Hyperf\ExceptionHandler\Listener\ErrorExceptionHandler;
use Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler;

return [
    'handler' => [
        'http' => [
            ErrorExceptionHandler::class,
            HttpExceptionHandler::class,
            // 处理业务异常
            BusinessExceptionHandler::class,
            // 处理验证器异常
            ValidationExceptionHandler::class,
            // 处理JWT异常
            JwtExceptionHandler::class,
            // 处理模型未找到异常
            ModelNotFoundException::class,
            // 处理应用异常
            AppExceptionHandler::class,
        ],
    ],
];

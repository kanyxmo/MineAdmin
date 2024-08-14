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

namespace App\Http\Admin\Controller\DataCenter;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\CurrentUser;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\DataCenter\UploadRequest;
use App\Http\Common\Middleware\AuthMiddleware;
use App\Http\Common\Result;
use App\Kernel\Annotation\Permission;
use App\Kernel\Swagger\Attributes\PageResponse;
use App\Kernel\Swagger\Attributes\ResultResponse;
use App\Schema\AttachmentSchema;
use App\Service\DataCenter\AttachmentService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Swagger\Annotation\Delete;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use Symfony\Component\Finder\SplFileInfo;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AuthMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
final class AttachmentController extends AbstractController
{
    public function __construct(
        protected readonly AttachmentService $service,
        protected readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/attachment/list',
        operationId: 'AttachmentList',
        summary: '附件列表',
        security: [['bearerAuth' => []]],
        tags: ['数据中心'],
    )]
    #[Permission(code: 'attachment:list')]
    #[PageResponse(instance: AttachmentSchema::class)]
    public function list(): Result
    {
        $params = $this->getRequest()->all();
        $params['current_user_id'] = $this->currentUser->id();
        return $this->success(
            $this->service->page($params, $this->getCurrentPage(), $this->getPageSize())
        );
    }

    #[Post(
        path: '/admin/attachment/upload',
        operationId: 'UploadAttachment',
        summary: '上传附件',
        security: [['bearerAuth' => []]],
        tags: ['数据中心'],
    )]
    #[Permission(code: 'attachment:upload')]
    #[ResultResponse(instance: new Result())]
    public function upload(UploadRequest $request): Result
    {
        $uploadFile = $request->file('file');
        $newTmpPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $uploadFile->getExtension();
        $uploadFile->moveTo($newTmpPath);
        $splFileInfo = new SplFileInfo($newTmpPath, '', '');
        return $this->success($this->service->upload($splFileInfo, $this->currentUser->id()));
    }

    #[Delete(
        path: '/admin/attachment/{id}',
        operationId: 'DeleteAttachment',
    )]
    #[Permission(code: 'attachment:delete')]
    #[ResultResponse(instance: new Result())]
    public function delete(int $id): Result
    {
        if (! $this->service->getRepository()->existsById($id)) {
            return $this->error(trans('attachment.attachment_not_exist'));
        }
        $this->service->deleteById($id);
        return $this->success();
    }
}

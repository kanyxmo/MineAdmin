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

namespace App\Http\Admin\Controller;

use App\Service\DataCenter\NoticeService;
use App\Service\DataCenter\AttachmentService;
use App\Service\Logs\LoginLogService;
use App\Service\Logs\OperLogService;
use App\Service\Permission\DeptService;
use App\Service\Permission\PostService;
use App\Service\Permission\RoleService;
use App\Service\Permission\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Mine\Annotation\Auth;
use Mine\MineController;
use Psr\Http\Message\ResponseInterface;

/**
 * 公共方法控制器
 * Class CommonController.
 */
#[Controller(prefix: 'system/common'), Auth]
class CommonController extends MineController
{
    #[Inject]
    protected UserService $userService;

    #[Inject]
    protected DeptService $deptService;

    #[Inject]
    protected RoleService $roleService;

    #[Inject]
    protected PostService $postService;

    #[Inject]
    protected NoticeService $noticeService;

    #[Inject]
    protected LoginLogService $loginLogService;

    #[Inject]
    protected OperLogService $operLogService;

    #[Inject]
    protected AttachmentService $attachmentService;

    /**
     * 获取用户列表.
     */
    #[GetMapping('getUserList')]
    public function getUserList(): ResponseInterface
    {
        return $this->success($this->userService->getPageList($this->request->all()));
    }

    /**
     * 通过 id 列表获取用户基础信息.
     */
    #[PostMapping('getUserInfoByIds')]
    public function getUserInfoByIds(): ResponseInterface
    {
        return $this->success($this->userService->getUserInfoByIds((array) $this->request->input('ids', [])));
    }

    /**
     * 获取部门树列表.
     */
    #[GetMapping('getDeptTreeList')]
    public function getDeptTreeList(): ResponseInterface
    {
        return $this->success($this->deptService->getSelectTree());
    }

    /**
     * 获取角色列表.
     */
    #[GetMapping('getRoleList')]
    public function getRoleList(): ResponseInterface
    {
        return $this->success($this->roleService->getList());
    }

    /**
     * 获取岗位列表.
     */
    #[GetMapping('getPostList')]
    public function getPostList(): ResponseInterface
    {
        return $this->success($this->postService->getList());
    }

    /**
     * 获取公告列表.
     */
    #[GetMapping('getNoticeList')]
    public function getNoticeList(): ResponseInterface
    {
        return $this->success($this->noticeService->getPageList($this->request->all()));
    }

    /**
     * 获取登录日志列表.
     */
    #[GetMapping('getLoginLogList')]
    public function getLoginLogPageList(): ResponseInterface
    {
        return $this->success($this->loginLogService->getPageList(array_merge($this->request->all(), ['username' => user()->getUsername()])));
    }

    /**
     * 获取操作日志列表.
     */
    #[GetMapping('getOperationLogList')]
    public function getOperLogPageList(): ResponseInterface
    {
        return $this->success($this->operLogService->getPageList(array_merge($this->request->all(), ['username' => user()->getUsername()])));
    }

    /**
     * 获取附件列表.
     */
    #[GetMapping('getResourceList')]
    public function getResourceList(): ResponseInterface
    {
        return $this->success($this->attachmentService->getPageList($this->request->all()));
    }

    /**
     * 清除所有缓存.
     */
    #[GetMapping('clearAllCache')]
    public function clearAllCache(): ResponseInterface
    {
        $this->userService->clearCache((string) user()->getId());
        return $this->success();
    }
}

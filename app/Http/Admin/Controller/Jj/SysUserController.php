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

namespace App\Http\Admin\Controller\Jj;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Permission\RoleRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Result;
use App\Http\CurrentUser;
use App\Schema\UserLoginLogSchema;
use App\Service\Jj\SysUserService;
use App\Service\LogStash\UserLoginLogService;
use App\Service\V1\J123EventService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Swagger\Annotation\Delete;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
final class SysUserController extends AbstractController
{
    public function __construct(
        protected readonly SysUserService $service,
        protected readonly CurrentUser $currentUser
    ) {}


    //根据 dept_id 获取所有用户
    #[Get(
        path: '/admin/Jj/sys_user/getUsersByDeptId',
    )]
    public function getUsersByDeptId(RequestInterface $request)
    {
        return  $this->success($this->service->getUsersByDeptId($request->input('dept_id')));
    }
}

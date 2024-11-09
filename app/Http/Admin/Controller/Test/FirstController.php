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

namespace App\Http\Admin\Controller\Test;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Permission\RoleRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Result;
use App\Http\CurrentUser;
use App\Schema\UserLoginLogSchema;
use App\Service\LogStash\UserLoginLogService;
use App\Service\V1\J123EventService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Swagger\Annotation\Delete;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\JsonContent;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Put;
use Hyperf\Swagger\Annotation\RequestBody;
use Mine\Access\Attribute\Permission;
use Mine\Swagger\Attributes\PageResponse;
use Mine\Swagger\Attributes\ResultResponse;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
final class FirstController extends AbstractController
{
    public function __construct(
        protected readonly J123EventService $service,
        protected readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/test/first',
        operationId: 'testFirst',
        summary: '测试列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['系统管理'],
    )]
    #[Permission(code: 'log:userLogin:list')]
//    #[PageResponse(instance: UserLoginLogSchema::class)]
    public function page(): Result
    {
        return $this->success(
            $this->service->page(
                $this->getRequestData(),
                $this->getCurrentPage(),
                $this->getPageSize()
            )
        );
    }

    #[Post(
        path: '/admin/test/create',
        operationId: 'testCreate',
        summary: '创建事故',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['事故管理'],
    )]
//    #[RequestBody(
//        content: new JsonContent(ref: RoleRequest::class)
//    )]
    #[Permission(code: 'permission:role:save')]
    #[ResultResponse(instance: new Result())]
    public function create(RequestInterface $request): Result
    {
        $this->service->create($request->all());
        return $this->success();
    }


    #[Delete(
        path: '/admin/test/delete',
//        operationId: 'roleDelete',
//        summary: '删除角色',
//        security: [['Bearer' => [], 'ApiKey' => []]],
//        tags: ['角色管理'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'permission:role:delete')]
    public function delete(RequestInterface $request): Result
    {
//        $this->service->deleteById($this->getRequestData());
        $this->service->deleteById($request->input('ids'));
        return $this->success();
    }

    #[Put(
        path: '/admin/test/{id}',
    )]
//    #[RequestBody(
//        content: new JsonContent(ref: RoleRequest::class)
//    )]
    #[Permission(code: 'permission:role:update')]
//    #[ResultResponse(instance: new Result())]
    public function save(int $id, RequestInterface $request): Result
    {
        $this->service->updateById($id, $request->all());
        return $this->success();
    }

//    #[Delete(
//        path: '/admin/user-login-log',
//        operationId: 'UserLoginLogDelete',
//        summary: '删除用户登录日志',
//        security: [['Bearer' => [], 'ApiKey' => []]],
//        tags: ['系统管理'],
//    )]
//    #[Permission(code: 'log:userLogin:delete')]
//    public function delete(RequestInterface $request): Result
//    {
//        $this->service->deleteById($request->input('ids'));
//        return $this->success();
//    }
}

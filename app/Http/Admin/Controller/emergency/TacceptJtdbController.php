<?php

namespace App\Http\Admin\Controller\emergency;

use App\Service\emergency\TacceptJtdbService as Service;
use App\Http\Admin\Request\emergency\TacceptJtdbRequest as Request;
use Hyperf\Swagger\Annotation as OA;
use App\Http\Admin\Controller\AbstractController;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Result;
use App\Http\CurrentUser;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use Mine\Access\Attribute\Permission;
use Hyperf\HttpServer\Annotation\Middleware;
use Mine\Swagger\Attributes\ResultResponse;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Put;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\Delete;



#[OA\Tag('车辆任务')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class TacceptJtdbController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/emergency/taccept_jtdb/list',
        operationId: 'emergency:taccept_jtdb:list',
        summary: '车辆任务列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['车辆任务'],
    )]
    #[Permission(code: 'emergency:taccept_jtdb:list')]
    public function pageList(): Result
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
        path: '/admin/emergency/taccept_jtdb',
        operationId: 'emergency:taccept_jtdb:create',
        summary: '新增车辆任务',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['车辆任务'],
    )]
    #[Permission(code: 'emergency:taccept_jtdb:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        $this->service->create(array_merge($request->validated(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/emergency/taccept_jtdb/{id}',
        operationId: 'emergency:taccept_jtdb:update',
        summary: '保存车辆任务',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['车辆任务'],
    )]
    #[Permission(code: 'emergency:taccept_jtdb:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/emergency/taccept_jtdb',
        operationId: 'emergency:taccept_jtdb:delete',
        summary: '删除车辆任务',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['车辆任务'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'emergency:taccept_jtdb:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

}

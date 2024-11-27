<?php

namespace App\Http\Admin\Controller\accident;

use App\Service\accident\J123AccidentNumberService as Service;
use App\Http\Admin\Request\accident\J123AccidentNumberRequest as Request;
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



#[OA\Tag('案件号管理')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class J123AccidentNumberController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/accident/j123_accident_number/list',
        operationId: 'accident:j123_accident_number:list',
        summary: '案件号管理列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['案件号管理'],
    )]
    #[Permission(code: 'accident:j123_accident_number:list')]
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
        path: '/admin/accident/j123_accident_number',
        operationId: 'accident:j123_accident_number:create',
        summary: '新增案件号管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['案件号管理'],
    )]
    #[Permission(code: 'accident:j123_accident_number:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        $this->service->create(array_merge($request->validated(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/accident/j123_accident_number/{id}',
        operationId: 'accident:j123_accident_number:update',
        summary: '保存案件号管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['案件号管理'],
    )]
    #[Permission(code: 'accident:j123_accident_number:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/accident/j123_accident_number',
        operationId: 'accident:j123_accident_number:delete',
        summary: '删除案件号管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['案件号管理'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'accident:j123_accident_number:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

}

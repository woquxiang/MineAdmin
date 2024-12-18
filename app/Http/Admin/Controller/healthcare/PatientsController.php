<?php

namespace App\Http\Admin\Controller\healthcare;

use App\Service\healthcare\PatientsService as Service;
use App\Http\Admin\Request\healthcare\PatientsRequest as Request;
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



#[OA\Tag('患者管理')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class PatientsController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/healthcare/patients/list',
        operationId: 'healthcare:patients:list',
        summary: '患者管理列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['患者管理'],
    )]
    #[Permission(code: 'healthcare:patients:list')]
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
        path: '/admin/healthcare/patients',
        operationId: 'healthcare:patients:create',
        summary: '新增患者管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['患者管理'],
    )]
    #[Permission(code: 'healthcare:patients:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        $this->service->create(array_merge($request->validated(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/healthcare/patients/{id}',
        operationId: 'healthcare:patients:update',
        summary: '保存患者管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['患者管理'],
    )]
    #[Permission(code: 'healthcare:patients:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/healthcare/patients',
        operationId: 'healthcare:patients:delete',
        summary: '删除患者管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['患者管理'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'healthcare:patients:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

}

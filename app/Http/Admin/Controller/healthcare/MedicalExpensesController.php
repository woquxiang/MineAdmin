<?php

namespace App\Http\Admin\Controller\healthcare;

use App\Service\healthcare\MedicalExpensesService as Service;
use App\Http\Admin\Request\healthcare\MedicalExpensesRequest as Request;
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



#[OA\Tag('费用管理')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class MedicalExpensesController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/healthcare/medical_expenses/list',
        operationId: 'healthcare:medical_expenses:list',
        summary: '费用管理列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['费用管理'],
    )]
    #[Permission(code: 'healthcare:medical_expenses:list')]
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
        path: '/admin/healthcare/medical_expenses',
        operationId: 'healthcare:medical_expenses:create',
        summary: '新增费用管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['费用管理'],
    )]
    #[Permission(code: 'healthcare:medical_expenses:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        $this->service->create(array_merge($request->validated(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/healthcare/medical_expenses/{id}',
        operationId: 'healthcare:medical_expenses:update',
        summary: '保存费用管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['费用管理'],
    )]
    #[Permission(code: 'healthcare:medical_expenses:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/healthcare/medical_expenses',
        operationId: 'healthcare:medical_expenses:delete',
        summary: '删除费用管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['费用管理'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'healthcare:medical_expenses:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

}

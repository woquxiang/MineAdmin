<?php

namespace App\Http\Admin\Controller\injury;

use App\Service\injury\InjuryClaimApplicationService as Service;
use App\Http\Admin\Request\injury\InjuryClaimApplicationRequest as Request;
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



#[OA\Tag('人伤直赔记录')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class InjuryClaimApplicationController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/injury/injury_claim_application/list',
        operationId: 'injury:injury_claim_application:list',
        summary: '人伤直赔记录列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['人伤直赔记录'],
    )]
    #[Permission(code: 'injury:injury_claim_application:list')]
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
        path: '/admin/injury/injury_claim_application',
        operationId: 'injury:injury_claim_application:create',
        summary: '新增人伤直赔记录',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['人伤直赔记录'],
    )]
    #[Permission(code: 'injury:injury_claim_application:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        //通过service创建
        $this->service->create(array_merge($request->all(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/injury/injury_claim_application/{id}',
        operationId: 'injury:injury_claim_application:update',
        summary: '保存人伤直赔记录',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['人伤直赔记录'],
    )]
    #[Permission(code: 'injury:injury_claim_application:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        //通过service更新
        $this->service->updateById($id, array_merge($request->all(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/injury/injury_claim_application',
        operationId: 'injury:injury_claim_application:delete',
        summary: '删除人伤直赔记录',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['人伤直赔记录'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'injury:injury_claim_application:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

    //详情
    #[Get(
        path: '/admin/injury/injury_claim_application/{id}',
        operationId: 'injury:injury_claim_application:details',
        summary: '人伤直赔记录详情',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['人伤直赔记录'],
    )]
    public function details(int $id): Result
    {
        return $this->success($this->service->details($id));
    }
}

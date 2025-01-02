<?php

namespace App\Http\Admin\Controller\injury;

use App\Service\injury\InjurySignatureService as Service;
use App\Http\Admin\Request\injury\InjurySignatureRequest as Request;
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



#[OA\Tag('签名信息')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class InjurySignatureController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/injury/injury_signature/list',
        operationId: 'injury:injury_signature:list',
        summary: '签名信息列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['签名信息'],
    )]
    #[Permission(code: 'injury:injury_signature:list')]
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
        path: '/admin/injury/injury_signature',
        operationId: 'injury:injury_signature:create',
        summary: '新增签名信息',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['签名信息'],
    )]
    #[Permission(code: 'injury:injury_signature:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        $this->service->create(array_merge($request->validated(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/injury/injury_signature/{id}',
        operationId: 'injury:injury_signature:update',
        summary: '保存签名信息',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['签名信息'],
    )]
    #[Permission(code: 'injury:injury_signature:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/injury/injury_signature',
        operationId: 'injury:injury_signature:delete',
        summary: '删除签名信息',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['签名信息'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'injury:injury_signature:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

}

<?php

namespace App\Http\Admin\Controller\feedback;

use App\Service\feedback\IssuesService as Service;
use App\Http\Admin\Request\feedback\IssuesRequest as Request;
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



#[OA\Tag('用户反馈')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class IssuesController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/feedback/issues/list',
        operationId: 'feedback:issues:list',
        summary: '用户反馈列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['用户反馈'],
    )]
    #[Permission(code: 'feedback:issues:list')]
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
        path: '/admin/feedback/issues',
        operationId: 'feedback:issues:create',
        summary: '新增用户反馈',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['用户反馈'],
    )]
    #[Permission(code: 'feedback:issues:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        $this->service->create(array_merge($request->all(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/feedback/issues/{id}',
        operationId: 'feedback:issues:update',
        summary: '保存用户反馈',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['用户反馈'],
    )]
    #[Permission(code: 'feedback:issues:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/feedback/issues',
        operationId: 'feedback:issues:delete',
        summary: '删除用户反馈',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['用户反馈'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'feedback:issues:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

}

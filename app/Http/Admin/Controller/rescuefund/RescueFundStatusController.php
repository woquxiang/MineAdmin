<?php

namespace App\Http\Admin\Controller\rescuefund;

use App\Service\rescuefund\RescueFundStatusService as Service;
use App\Http\Admin\Request\rescuefund\RescueFundStatusRequest as Request;
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



#[OA\Tag('垫付管理')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class RescueFundStatusController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/rescuefund/rescue_fund_status/list',
        operationId: 'rescuefund:rescue_fund_status:list',
        summary: '垫付管理列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['垫付管理'],
    )]
    #[Permission(code: 'rescuefund:rescue_fund_status:list')]
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
        path: '/admin/rescuefund/rescue_fund_status',
        operationId: 'rescuefund:rescue_fund_status:create',
        summary: '新增垫付管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['垫付管理'],
    )]
    #[Permission(code: 'rescuefund:rescue_fund_status:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        $this->service->create(array_merge($request->validated(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/rescuefund/rescue_fund_status/{id}',
        operationId: 'rescuefund:rescue_fund_status:update',
        summary: '保存垫付管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['垫付管理'],
    )]
    #[Permission(code: 'rescuefund:rescue_fund_status:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/rescuefund/rescue_fund_status',
        operationId: 'rescuefund:rescue_fund_status:delete',
        summary: '删除垫付管理',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['垫付管理'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'rescuefund:rescue_fund_status:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

}

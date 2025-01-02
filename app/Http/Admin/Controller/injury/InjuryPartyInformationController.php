<?php

namespace App\Http\Admin\Controller\injury;

use App\Service\injury\InjuryPartyInformationService as Service;
use App\Http\Admin\Request\injury\InjuryPartyInformationRequest as Request;
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



#[OA\Tag('人伤当事人')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class InjuryPartyInformationController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/injury/injury_party_information/list',
        operationId: 'injury:injury_party_information:list',
        summary: '人伤当事人列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['人伤当事人'],
    )]
    #[Permission(code: 'injury:injury_party_information:list')]
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
        path: '/admin/injury/injury_party_information',
        operationId: 'injury:injury_party_information:create',
        summary: '新增人伤当事人',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['人伤当事人'],
    )]
    #[Permission(code: 'injury:injury_party_information:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        $this->service->create(array_merge($request->validated(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/injury/injury_party_information/{id}',
        operationId: 'injury:injury_party_information:update',
        summary: '保存人伤当事人',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['人伤当事人'],
    )]
    #[Permission(code: 'injury:injury_party_information:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/injury/injury_party_information',
        operationId: 'injury:injury_party_information:delete',
        summary: '删除人伤当事人',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['人伤当事人'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'injury:injury_party_information:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

    //通过直赔ID合成签名PDF
    #[Post(
        path: '/admin/injury/injury_party_information/merge_signature',
        // operationId: 'injury:injury_party_information:merge_signature',
        summary: '签名合成',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['人伤当事人'],
    )]
    public function mergeSignature(): Result
    {
        $id = $this->getRequestData()['id'];

        $url = $this->service->mergeSignature($id);
        return $this->success([
            'url' => $url
        ]);
    }
}

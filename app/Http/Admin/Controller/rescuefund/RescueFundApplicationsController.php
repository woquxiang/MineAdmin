<?php

namespace App\Http\Admin\Controller\rescuefund;

use App\Client\RoadFund\RoadFundApplication;
use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Repository\rescuefund\FilesRepository;
use App\Service\rescuefund\FilesService;
use App\Service\rescuefund\RegionsService;
use App\Service\rescuefund\RescueFundApplicationsService as Service;
use App\Http\Admin\Request\rescuefund\RescueFundApplicationsRequest as Request;
use App\Service\rescuefund\RescueFundStatusService;
use Hyperf\HttpServer\Contract\RequestInterface;
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



#[OA\Tag('道路基金')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class RescueFundApplicationsController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        protected readonly FilesService $filesService,
        protected readonly FilesRepository $filesRepository,
        private readonly CurrentUser $currentUser,
        protected readonly RegionsService $regionsService,
        protected readonly RoadFundApplication $roadFundApplication,
        protected readonly RescueFundStatusService $rescueFundStatusService
    ) {}

    #[Get(
        path: '/admin/rescuefund/rescue_fund_applications/list',
        operationId: 'rescuefund:rescue_fund_applications:list',
        summary: '道路基金列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['道路基金'],
    )]
    #[Permission(code: 'rescuefund:rescue_fund_applications:list')]
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
        path: '/admin/rescuefund/rescue_fund_applications',
        operationId: 'rescuefund:rescue_fund_applications:create',
        summary: '新增道路基金',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['道路基金'],
    )]
    #[Permission(code: 'rescuefund:rescue_fund_applications:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        $this->service->create(array_merge($request->post(), [
            'created_by' => $this->currentUser->id(),
            'is_approve'=>0
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/rescuefund/rescue_fund_applications/{id}',
        operationId: 'rescuefund:rescue_fund_applications:update',
        summary: '保存道路基金',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['道路基金'],
    )]
    #[Permission(code: 'rescuefund:rescue_fund_applications:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Get(
        path: '/admin/rescuefund/rescue_fund_applications/{id}',
    )]
    #[Permission(code: 'rescuefund:rescue_fund_applications:detail')]
    public function details(int $id,RequestInterface $request): Result
    {
        $result = $this->service->findById($id);
        if(!$result){
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '没有找到记录');
        }

        $result1 = $this->roadFundApplication->getApplicationById(['id'=>$result['sqxx_id']]);
       if($result['sqxx_id']){
           $this->roadFundApplication->deleteToken();
           $this->rescueFundStatusService->syncDataByApplicationId($result->id);
       }

        return $this->success(array_merge($result->toArray(),$this->regionsService->getRegionNamesByFundId($id)));
    }

    #[Delete(
        path: '/admin/rescuefund/rescue_fund_applications',
        operationId: 'rescuefund:rescue_fund_applications:delete',
        summary: '删除道路基金',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['道路基金'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'rescuefund:rescue_fund_applications:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

    #[Put(
        path: '/admin/rescuefund/rescue_fund_applications/{id}/approve',
        operationId: 'rescuefund:rescue_fund_applications:approve',
        summary: '审核道路基金申请',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['道路基金'],
    )]
    #[Permission(code: 'rescuefund:rescue_fund_applications:approve')]
    public function approve(int $id, RequestInterface $request)
    {
        try {
            // 获取审核结果：0表示未通过，1表示通过
            $isApproved = (int) $request->post('is_approved');

            // 校验审核结果是否有效
            if (!in_array($isApproved, [0, 1 ,2], true)) {
                return $this->danger('不支持操作');
            }

            // 调用服务层的审核方法
            $this->service->updateById($id, ['is_approved'=> $isApproved]);

            return $this->success('操作成功');
        } catch (\Throwable $e) {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '审核操作失败');
        }
    }

    #[Get(
        path: '/admin/rescuefund/rescue_fund_applications/{id}/view_files',
    )]
    #[Permission(code: 'rescuefund:rescue_fund_applications:detail')]
    public function view_files(int $id,RequestInterface $request): Result
    {
        return $this->success($this->filesService->findFilesByApplicationId($id));
    }

    #[Post(
        path: '/admin/rescuefund/rescue_fund_applications/sync_file',
    )]
    #[Permission(code: 'rescuefund:rescue_fund_applications:detail')]
    public function sync_file(RequestInterface $request): Result
    {
        $id = $request->post('id');
        $this->filesService->syncFile($id);

        return $this->success($this->filesRepository->findById($id));
    }

    #[Post(
        path: '/admin/rescuefund/rescue_fund_applications/apply',
    )]
    #[Permission(code: 'rescuefund:rescue_fund_applications:detail')]
    public function apply(RequestInterface $request): Result
    {
        $id = $request->post('id');
        $this->service->apply((int) $id);

        return $this->success();
    }
}

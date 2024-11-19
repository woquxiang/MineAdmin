<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Http\Api\Controller\V1;

use App\Client\RoadFund\RoadFundApplication;
use App\Http\Api\Middleware\TokenMiddleware;
use App\Http\Api\Request\UploadRequest;
use App\Http\Api\Controller\AbstractController;
use App\Http\Common\Result;
use App\Http\CurrentUser;
use App\Model\Jj\Accident;
use App\Model\Jj\AccidentParty;
use App\Model\Jj\AccidentWounded;
use App\Model\Jj\Driver;
use App\Model\Jj\PartyDirectIndemnity;
use App\Service\PassportService;
use App\Service\rescuefund\FilesService;
use App\Service\rescuefund\RegionsService;
use App\Service\rescuefund\RescueFundApplicationsService;
use App\Service\V1\AttachmentService;
use App\Service\V1\FrontmachineService;
use App\Service\V1\J123EventService;
use App\Service\V1\J123PeopleService;
use App\Service\XPassportService;
use Hyperf\Collection\Arr;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Stringable\Str;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use EasyWeChat\MiniApp\Application;
use Symfony\Component\Finder\SplFileInfo;
use App\Http\Admin\Request\rescuefund\RescueFundApplicationsRequest as Request;
use Hyperf\Validation\Annotation\Scene;




#[HyperfServer(name: 'http')]
#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
final class RescueFundController extends AbstractController
{
    public function __construct(
        private readonly XPassportService $passportService,
        private readonly CurrentUser $currentUser,
        protected readonly J123EventService $j123EventService,
        protected readonly J123PeopleService $j123PeopleService,
        private readonly FrontmachineService $frontmachineService,
        protected readonly RoadFundApplication $roadFundApplication,
        protected readonly RegionsService $regionsService,
        protected readonly RescueFundApplicationsService $service,
        protected readonly FilesService $filesService,
    ) {}

    #[Post(
        path: '/v1/rescue-fund/apply',
    )]
    #[Scene(scene:'frontendCreate')]
    public function create(Request $request)
    {
        try {
            $application =  $this->service->create(array_merge($request->post(), [
                'created_by' => $this->currentUser->id(),
            ]));

            // 获取传入的文件列表字段
            $files = $request->post('file_dto');  // 假设文件字段名为 'file_dto'
            if ($files) {
                $files = json_decode($files,true) ?? [];
                // 将文件数据传递给 FilesService 处理文件存储
                $this->filesService->storeApplicationFiles((int) $application->id, $files);
            }

            return $this->success();
        } catch (\Throwable $e) {
            return $this->error('创建失败');
        }
    }

    #[Post(
        path: '/v1/rescue-fund/regions',
    )]
    public function regions(RequestInterface $request)
    {
        $for  = file_get_contents(BASE_PATH . '/regions.json');

        return $this->success(json_decode($for,true));
    }

    #[Post(
        path: '/v1/rescue-fund/list',
    )]
    public function list(RequestInterface $request)
    {
        try {
            $params = $this->getRequestData();
            $params['created_by'] = $this->currentUser->id();

            return $this->success(
                $this->service->page(
                    $params,
                    $this->getCurrentPage(),
                    $this->getPageSize()
                )
            );

        } catch (\Throwable $e) {
            return $this->error('获取列表失败');
        }
    }

    #[Post(
        path: '/v1/rescue-fund/details',
    )]
    public function details(RequestInterface $request)
    {
        try {
            $id = (int) $request->post('id'); // 从请求获取记录ID
            $createdBy = $this->currentUser->id(); // 当前用户ID

            // 获取详情
            $detail = $this->service->getUserDetail($id, $createdBy);

            if (!$detail) {
                return $this->error('记录不存在或无权限查看');
            }

            return $this->success($detail);
        } catch (\Throwable $e) {
            return $this->error('获取详情失败');
        }
    }

    #[Post(
        path: '/v1/rescue-fund/upload',
    )]
    public function upload(UploadRequest $request): Result
    {
        $userId = $this->currentUser->id(); // 当前用户ID

        $uploadFile = $request->file('file');
        $newTmpPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $uploadFile->getExtension();
        $uploadFile->moveTo($newTmpPath);
        $splFileInfo = new SplFileInfo($newTmpPath, '', '');

        return $this->success(
            Arr::only(
                $this->filesService->upload($splFileInfo, $uploadFile, $userId)?->toArray() ?: [],
                ['id','created_at','url']
            )
        );
    }

}

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
use App\Exception\BusinessException;
use App\Http\Api\Middleware\TokenMiddleware;
use App\Http\Api\Request\UploadRequest;
use App\Http\Api\Controller\AbstractController;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Http\Common\ResultCode;
use App\Service\content\NewsService as Service;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Stringable\Str;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use EasyWeChat\MiniApp\Application;
use Symfony\Component\Finder\SplFileInfo;


#[HyperfServer(name: 'http')]
//#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
//#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
final class NewsController extends AbstractController
{

    public function __construct(
        private readonly Service $service,
    ) {}

    #[Post(
        path: '/v1/news/list',
    )]
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
        path: '/v1/news/details',
    )]
    public function details(RequestInterface $request)
    {
        $id = $request->post('id') ?? '';
        $result = $this->service->findById((int) $id);
        if(!$result){
            return $this->error('未找到');
        }

        return $this->success($result);
    }
}

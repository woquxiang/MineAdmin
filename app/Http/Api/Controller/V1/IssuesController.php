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
use App\Http\Admin\Request\feedback\IssuesRequest as Request;
use App\Http\Api\Middleware\TokenMiddleware;
use App\Http\Api\Request\UploadRequest;
use App\Http\Api\Controller\AbstractController;
use App\Http\Common\Result;
use App\Http\Common\ResultCode;
use App\Http\CurrentUser;
use App\Service\feedback\IssuesService as Service;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Stringable\Str;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use EasyWeChat\MiniApp\Application;
use Symfony\Component\Finder\SplFileInfo;


#[HyperfServer(name: 'http')]
#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
final class IssuesController extends AbstractController
{

    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser,
    ) {}

    #[Post(
        path: '/v1/issues',
    )]
    public function create(Request $request): Result
    {
        $this->service->create(array_merge($request->all(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }
}

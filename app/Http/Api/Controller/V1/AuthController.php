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

use App\Http\Api\Middleware\TokenMiddleware;
use App\Http\Api\Request\V1\UserRequest;
use App\Http\Common\Controller\AbstractController;
use App\Http\Common\Result;
use App\Http\CurrentUser;
use App\Model\Enums\User\Type;
use App\Service\XPassportService;
use Hyperf\Collection\Arr;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use EasyWeChat\MiniApp\Application;


#[HyperfServer(name: 'http')]
final class AuthController extends AbstractController
{

    public function __construct(
        private readonly XPassportService $passportService,
        private readonly CurrentUser $currentUser,
    )
    {
    }

    #[Post(
        path: '/v1/auth/login',
        operationId: 'ApiAuthLogin',
        summary: '小程序用户登录',
        tags: ['api'],
    )]
    public function loginApi(RequestInterface $request): Result
    {
        $code = (string) $request->input('code');

        // 调用服务层进行微信小程序登录
        $result = $this->passportService->loginWithMiniApp($code);

        return $this->success($result);
    }

    #[Post(
        path: '/v1/auth/phone',
        operationId: 'ApiAuthGetPhone',
        summary: '获取用户手机号',
        tags: ['api'],
    )]
    #[Middleware(middleware: TokenMiddleware::class, priority: 100)]
    public function getPhoneNumber(RequestInterface $request): Result
    {
        $code = (string) $request->input('code');

        $phoneNumber = $this->passportService->getPhoneNumber($this->currentUser->id(),$code);

        return $this->success(['phoneNumber' => $phoneNumber]);
    }
}
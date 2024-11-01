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

use App\Http\Api\Request\V1\UserRequest;
use App\Http\Common\Controller\AbstractController;
use App\Http\Common\Result;
use App\Model\Enums\User\Type;
use App\Service\XPassportService;
use Hyperf\Collection\Arr;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use EasyWeChat\MiniApp\Application;


#[HyperfServer(name: 'http')]
final class AuthController extends AbstractController
{

    public function __construct(
        private readonly XPassportService $passportService,
    )
    {
    }

    #[Post(
        path: '/v1/auth/login',
        operationId: 'ApiAuthLogin',
        summary: '小程序 用户登录',
        tags: ['api'],
    )]
    public function loginApi(RequestInterface $request): Result
    {
        $username = (string) $request->input('username');
        $password = (string) $request->input('password');
        //$ip = Arr::first(array: $request->getClientIps(), callback: static fn ($val) => $val ?: null, default: '0.0.0.0');
        $ip =  '0.0.0.0';
        $browser = $request->header('User-Agent') ?: 'unknown';
        // todo 用户系统的获取
        $os = $request->header('User-Agent') ?: 'unknown';

        return $this->success(
            $this->passportService->loginApi(
                $username,
                $password,
                Type::USER,
                $ip,
                $browser,
                $os
            )
        );
    }
}
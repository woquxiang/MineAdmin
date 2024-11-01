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
use App\Http\Common\Controller\AbstractController;
use App\Http\Common\Result;
use App\Http\CurrentUser;
use App\Service\PassportService;
use App\Service\XPassportService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Stringable\Str;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use EasyWeChat\MiniApp\Application;


#[HyperfServer(name: 'http')]
//#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
final class DemoController extends AbstractController
{

    public function __construct(
        private readonly XPassportService $passportService,
        private readonly CurrentUser $currentUser,
    ) {}

    #[Post(
        path: '/v1/demo/mm',
        operationId: 'ApiV1Demo',
        summary: '测试',
        tags: ['api'],
    )]
    public function demo(RequestInterface $request)
    {
        $config = config('easywechat');  // 获取配置

        $app = new Application($config);

        try {

//            $response = $app->getClient()->get('/sns/jscode2session', [
//                'appid' => $config['app_id'], // 小程序的 appid
//                'secret' => $config['secret'], // 小程序的 app secret
//                'js_code' => '0d3Nrp0w30iaL339Fw1w3cOHYp2Nrp0V', // 获取到的 code
//                'grant_type' => 'authorization_code',
//            ])->toArray();

            $response =  $app->getClient()->postJson('wxa/business/getuserphonenumber', [
                'code'=>"521e97570378edd4fe5664f264703a25983b8949dd2a47efaa5e221022e6769d"
            ])->toArray();

            //            {"session_key":"vcz+uiYwuQYRDa67W6CXrA==","openid":"o1bl25ESp5kZvp3snPtZSK8LdAL0"}
            return $this->success($response);
            return $response;
        } catch (\Throwable $e) {
            // 失败
            print_r($e->getMessage());
        }

    }

    #[Post(
        path: '/v1/demo/mm1',
        operationId: 'ApiV1Demo',
        summary: '测试',
        tags: ['api'],
    )]
    public function demo1(RequestInterface $request)
    {
        $userId = $this->passportService->_getApiUserId();
        var_dump($userId);
//        $userId = $this->currentUser->id2('eyJ0eXAiO3iJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzAyNTkwODksIm5iZiI6MTczMDI1OTA4OSwiZXhwIjoxNzMwMjYyNjg5LCJqdGkiOiIzIn0.T0-BSy5TaM2sdedTk4aQx0tiqLtaCC-9HmajlyLm9E4');

        return $userId;
    }
}

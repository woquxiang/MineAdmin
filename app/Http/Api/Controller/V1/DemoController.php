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
use App\Model\Jj\Accident;
use App\Model\Jj\AccidentParty;
use App\Model\Jj\AccidentWounded;
use App\Model\Jj\Driver;
use App\Model\Jj\PartyDirectIndemnity;
use App\Service\PassportService;
use App\Service\XPassportService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Stringable\Str;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use EasyWeChat\MiniApp\Application;


#[HyperfServer(name: 'http')]
#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
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

            $response = $app->getClient()->get('/sns/jscode2session', [
                'appid' => $config['app_id'], // 小程序的 appid
                'secret' => $config['secret'], // 小程序的 app secret
                'js_code' => '0a3WZM000LZm7T1gzE0008L0sA0WZM0r', // 获取到的 code
                'grant_type' => 'authorization_code',
            ])->toArray();

//            $response =  $app->getClient()->postJson('wxa/business/getuserphonenumber', [
//                'code'=>"521e97570378edd4fe5664f264703a25983b8949dd2a47efaa5e221022e6769d"
//            ])->toArray();

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
//    #[Middleware(middleware: TokenMiddleware::class, priority: 100)]
    public function demo1(RequestInterface $request): Result
    {
        //$a = Accident::select(['id','na_reason'])->where('status','>',4)->firstOrFail();
        //$sb = $this->currentUser->user()['login_ip'];
        //return $this->success($sb);
        //事故当事人
        $driver_id = Driver::select()->where('id_card_num','422202199009277511')->value('id');
        $accident_id1 = [];
        if(NULL != $driver_id){
            $accident_id1 = AccidentParty::select()->where('driver_id',$driver_id)->pluck('accident_id')->toArray();
        }
        //伤者
        $direct_indemnity_ids = AccidentWounded::select()->where('identity_num','422202199009277511')->pluck('direct_indemnity_id')->toArray();


        $accident_id2 = [];
        if(!empty($direct_indemnity_ids)){
            $accident_id2 = PartyDirectIndemnity::select()->whereIn('id',$direct_indemnity_ids)->pluck('accident_id')->toArray();
        }

        $accident_ids = array_unique(array_merge($accident_id1,$accident_id2));

        $result = Accident::select(['id','status','accident_time','accident_address'])
            ->whereIn('id',$accident_ids)
            ->orderBy('id', 'desc')
            ->get();

        return $this->success($result);

        return $accident_ids;

        $userId = $this->passportService->_getApiUserId();
        var_dump($userId);
//        $userId = $this->currentUser->id2('eyJ0eXAiO3iJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzAyNTkwODksIm5iZiI6MTczMDI1OTA4OSwiZXhwIjoxNzMwMjYyNjg5LCJqdGkiOiIzIn0.T0-BSy5TaM2sdedTk4aQx0tiqLtaCC-9HmajlyLm9E4');

        return $userId;
    }
}

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
use App\Http\Api\Request\UploadRequest;
use App\Http\Common\Controller\AbstractController;
use App\Http\Common\Result;
use App\Http\CurrentUser;
use App\Model\Jj\Accident;
use App\Model\Jj\AccidentParty;
use App\Model\Jj\AccidentWounded;
use App\Model\Jj\Driver;
use App\Model\Jj\PartyDirectIndemnity;
use App\Service\PassportService;
use App\Service\V1\AttachmentService;
use App\Service\V1\FrontmachineService;
use App\Service\V1\J123EventService;
use App\Service\V1\J123PeopleService;
use App\Service\XPassportService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Stringable\Str;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use EasyWeChat\MiniApp\Application;
use Symfony\Component\Finder\SplFileInfo;
use App\Service\injury\InjuryClaimApplicationService;
use App\Service\injury\InjuryPartyInformationService;
use App\Repository\Jj\SysUserRepository;

#[HyperfServer(name: 'http')]
//#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
final class HospitalController extends AbstractController
{

    public function __construct(
        protected readonly AttachmentService $service,
        protected readonly InjuryClaimApplicationService $injuryClaimApplicationService,
        protected readonly InjuryPartyInformationService $injuryPartyInformationService,
        protected readonly SysUserRepository $sysUserRepository
    ) {}

    //医院请求接口 查询直赔信息
    #[Post(
        path: '/v1/hospital/req',
        operationId: 'hospital-req',
        summary: '医院-请求',
        tags: ['api'],
    )]
    public function req(RequestInterface $request)
    {
        try{
            //解密
            $directIndemnityId = $this->injuryClaimApplicationService->decryptData($request->post('directIndemnityId'));

            //通过直赔ID 查询直赔信息
            return $this->success($this->injuryPartyInformationService->findByDirectCompensationId($directIndemnityId));
        }catch(\Throwable $e){
            print_r($e->getMessage());
            return $this->error('没有找到直赔信息');
        }
    }

    //医院当天所有伤者列表
    #[Post(
        path: '/v1/hospital/today',
        operationId: 'hospital-today',
        summary: '医院-当天伤者列表',
        tags: ['api'],
    )]
    public function today(RequestInterface $request)
    {
        $hospital = $this->sysUserRepository->getUserByNickName($request->post('hospital') ?? '');
        if(!$hospital){
            return $this->error('医院不存在');
        }

        //time格式是 20210817

        $hospitalId = $hospital->user_id;
        $time = $request->post('time');
        if(!$time){
            return $this->error('时间不能为空');
        }
        $time = date("Y-m-d", strtotime($time));

        return $this->success($this->injuryPartyInformationService->getTodayInjuredList($hospitalId, $time));
    }
}

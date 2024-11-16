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


#[HyperfServer(name: 'http')]
#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
final class AccidentController extends AbstractController
{

    public function __construct(
        private readonly XPassportService $passportService,
        private readonly CurrentUser $currentUser,
        protected readonly J123EventService $j123EventService,
        protected readonly J123PeopleService $j123PeopleService,
        private readonly FrontmachineService $frontmachineService,
        protected readonly RoadFundApplication $roadFundApplication
    ) {}

    #[Post(
        path: '/v1/accident/list',
    )]
    public function page(RequestInterface $request)
    {
        $params = $this->getRequestData();

        // 将身份证和姓名加入到查询参数中
        $userInfo = $this->currentUser->user();

        $params['id_card_number'] = preg_replace('/(\w{4})\w*(\w{4})/','$1**********$2',$userInfo['id_card_number']);
        $params['id_card_name'] = $userInfo['id_card_name'];

        return $this->success(
            $this->j123EventService->page(
                $params,
                $this->getCurrentPage(),
                $this->getPageSize()
            )
        );
    }

    #[Post(
        path: '/v1/accident/details',
    )]
    public function details(RequestInterface $request)
    {
        $file_path = BASE_PATH . "/storage/uploads/2024-10-24/66004abc-2f13-421e-8943-da066074f3dd.png";

//        $result = $this->roadFundApplication->uploadFile(['file'=>$file_path]);
        $result = $this->roadFundApplication->getFileViewUrl(['fileId'=>'850e7ca326a94e17a21f28318d3b4a22']);

        var_dump($result);

        // 获取 POST 请求中的事故编号
        $accidentNumber = $request->post('accident_number');

        // 如果事故编号为空，则返回错误
        if (empty($accidentNumber)) {
            return $this->error('事故编号不能为空');
        }

        $userInfo = $this->currentUser->user();
        $userIdCard = preg_replace('/(\w{4})\w*(\w{4})/','$1**********$2',$userInfo['id_card_number']);

        // 查询当前用户是否与指定事故相关
        $check = $this->j123PeopleService->checkUserInvolvement($accidentNumber, $userIdCard);

        // 如果当前用户的身份证号未关联此事故，则返回无权限错误
        if (!$check) {
            return $this->error('未找到该事故记录或您无权查看此记录');
        }

        // 查询事故信息
        $accident = $this->j123EventService->findByAccidentNumber($accidentNumber);

        $people = $this->j123PeopleService->findByAccidentNumber($accidentNumber);// 查询当事人信息
        $attachment = $this->j123EventService->getAttachmentsByAccidentNumber($accidentNumber);//查询附件

        // 将当事人信息，附件信息放入事故信息中
        $accident->attachment = $attachment;
        $accident->people = $people;

        // 返回事故信息和当事人信息
        return $this->success([
            'accident' => $accident
        ]);
    }
}

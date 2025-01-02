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

use App\Client\Hospital\Hospital120;
use App\Client\Hospital\HospitalA;
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
use App\Repository\rescuefund\RescueFundRegionsRepository;
use App\Service\healthcare\TrafficIncidentsService;
use App\Service\PassportService;
use App\Service\rescuefund\RegionsService;
use App\Service\V1\AttachmentService;
use App\Service\V1\FrontmachineService;
use App\Service\V1\J123EventService;
use App\Service\V1\J123PeopleService;
use App\Service\XPassportService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Redis\Redis;
use Hyperf\Stringable\Str;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use EasyWeChat\MiniApp\Application;
use Symfony\Component\Finder\SplFileInfo;
use App\Service\emergency\TacceptJtdbService;

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
        protected readonly RoadFundApplication $roadFundApplication,
        protected readonly RegionsService $regionsService,
        protected readonly RescueFundRegionsRepository $rescueFundRegionsRepository,
        protected readonly TrafficIncidentsService $trafficIncidentsService,
        protected readonly TacceptJtdbService $tacceptJtdbService,
        protected readonly Redis $redis,
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

        // 获取 POST 请求中的事故编号
        $accidentNumber = $request->post('accident_number');

        $hospitalAService = new HospitalA();
        $result = $hospitalAService->queryFromHospital('select');

        //var_dump($result);


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

    #[Post(
        path: '/v1/accident/test',
    )]
    public function test(RequestInterface $request)
    {
        $sql = $request->post('sql','');
        $type = $request->post('type',1);

        if(1 == $type){
            $hospitalService = new HospitalA();
        }else{
            $hospitalService = new Hospital120();
        }

        $result = $hospitalService->queryFromHospital($sql);
        return $this->success($result);

//        if (env('APP_ENV') === 'prod') {
//            // 生产环境逻辑
//             return $this->success($result);
//        } else {
//            // 开发环境逻辑
//            return $result ;
//        }
    }

    #[Post(
        path: '/v1/accident/alldata',
    )]
    public function alldata(RequestInterface $request)
    {
        $id = $request->post('id','');

        //$ser = $this->trafficIncidentsService->queryAllFromHospital();

        $ser = $this->tacceptJtdbService->updateTacceptJtdb();


        //$this->redis->rPush('accident_ids_list', $id);

        //$hospitalAService = new HospitalA();
        //$result = $hospitalAService->queryFromHospital($sql);
        return $ser ;
//        return $this->success($result);
    }

    #[Post(
        path: '/v1/accident/test222',
    )]
    public function test222(RequestInterface $request,ResponseInterface $response)
    {
        //用 php header函数 重定向到https://baidu.com
       // return header("Location: https://baidu.com");

        return $response->redirect('https://baidu.com');


        return 333;

        $id = $request->post('id','');

        $serverParams = $request->getServerParams();
        print_r($serverParams);
        if (isset($serverParams['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $serverParams['HTTP_X_FORWARDED_FOR']);
            $realIp = trim($ips[0]);
            $serverParams['REMOTE_ADDR'] = $realIp;
            print_r(333);
            //$request = $request->withServerParams($serverParams);
        }
        print_r(444);

        //$ser = $this->trafficIncidentsService->updateAllDataByAccidentIdAndPatientId('20241209-00785',7);

        $this->redis->lPush('accident_ids_list', $id);

        //$hospitalAService = new HospitalA();
        //$result = $hospitalAService->queryFromHospital($sql);
        return [] ;
//        return $this->success($result);
    }

    #[Post(
        path: '/v1/accident/demox',
    )]
    public function demox()
    {
        $options = new Options();


        print_r(33333);

        $options->set('defaultFont', 'SimSun'); // 设置默认字体（可选）
        $dompdf = new Dompdf($options);

        $html = '';

        $html .= <<<HTML
    
<!DOCTYPE html>
<html lang="zh-CN">
<head>
      <meta charset="UTF-8">
      <title>发货单</title>
        <style>
            body {
      font-family: SimSun;
      line-height: 1;
      font-size: 12px;
    }
        </style>
    </head>
    <body>
        <h1>测asdfafd试 PDFaadsfa</h1>
        <h1 style="color: red;font-weight: 500;">测asdfafd试 PDFaadsfa</h1>
        <p>这是一个使用 Dompdf 生成的 PDF 示例，包含中文。</p >
        <p style="font-weight: 903330;">这是一个使用 Dompdf 生成的 PDF 示例，包含中文。</p >
    </body>
    </html>
HTML;

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();



        if (file_put_contents('fddxxxxd.pdf', $output) === false) {
            throw new \RuntimeException('无法写入 PDF 文件到指定路径！');
        }

        return $this->success('PDF 生33成成功！路径：');
    }
}

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


#[HyperfServer(name: 'http')]
//#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
final class FrontmachineController extends AbstractController
{

    public function __construct(
        private readonly XPassportService $passportService,
        private readonly CurrentUser $currentUser,
        protected readonly AttachmentService $service,
        protected readonly J123EventService $j123EventService,
        protected readonly J123PeopleService $j123PeopleService,
        private readonly FrontmachineService $frontmachineService
    ) {}

    #[Post(
        path: '/v1/front-machine/i1',
        operationId: 'front-machine-i1',
        summary: '交警-1',
        tags: ['api'],
    )]
    public function i1(RequestInterface $request)
    {
        $params_data = $request->post('data');

        $params_data = str_replace("'", '"', $params_data);
        $params_data = json_decode($params_data,true);

        foreach ($params_data as $_data) {
            $info = $_data['basic'];
            $people = $_data['people'];

            // 调用 FrontmachineService 处理创建或更新
            $this->frontmachineService->createOrUpdateAccidentRecords($info, $people);
        }

//        foreach ($params_data as $_data){
//            $info = $_data['basic'];
//            $people = $_data['people'];
//
//            $this->j123EventService->create([
//                'accident_number'=>$info[0] ?? '',
//                'event_date'=> $info[1] ?? date("Y-m-d H:i"),
//                'weather'=>$info[2] ?? '',
//                'location'=>$info[3] ?? '',
//                'accident_scenario'=>$info[4] ?? '',
//                'accident_type'=>$info[5] ?? '',
//                'data_source'=>$info[6] ?? '',
//                'handling_method'=>$info[7] ?? '',
//                'management_department'=>$info[8] ?? '',
//                'accident_status'=>$info[9] ?? '',
//            ]);
//
//            foreach ($people as $_people){
//                if(count($_people) == 5){//人
//                    $this->j123PeopleService->create([
//                        'accident_number'=>$info[0],
//                        'name'=>$_people[0] ?? '',
//                        'id_number'=>$_people[1] ?? '',
//                        'vehicle_type'=>$_people[2] ?? '',
//                        'phone'=>$_people[3] ?? '',
//                        'responsibility'=>$_people[4] ?? '',
//                    ]);
//                }elseif (count($_people) == 8){//车
//                    $this->j123PeopleService->create([
//                        'accident_number'=>$info[0],
//                        'name'=>$_people[0] ?? '',
//                        'id_number'=>$_people[1] ?? '',
//                        'vehicle_type'=>$_people[2] ?? '',
//                        'phone'=>$_people[3] ?? '',
//                        'car_type'=>$_people[4] ?? '',
//                        'license_plate'=>$_people[5] ?? '',
//                        'insurance_company'=>$_people[6] ?? '',
//                        'responsibility'=>$_people[7] ?? '',
//                    ]);
//                }
//            }
//        }




//        $result =  $this->j123EventService->findById(2);
        return $this->success();
    }

    #[Post(
        path: '/v1/front-machine/i2',
        operationId: 'front-machine-i2',
        summary: '交警-2',
        tags: ['api'],
    )]
    public function i2(UploadRequest $request): Result
    {
        //return $this->success($request->file('file'));
        $uploadFile = $request->file('file');
        $newTmpPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $uploadFile->getExtension();
        $uploadFile->moveTo($newTmpPath);
        $splFileInfo = new SplFileInfo($newTmpPath, '', '');
        return $this->success(
            $this->service->upload($splFileInfo, $uploadFile, 1)
        );
    }

    #[Post(
        path: '/v1/front-machine/i3',
        operationId: 'front-machine-i3',
        summary: '交警-2',
        tags: ['api'],
    )]
    public function i3(RequestInterface $request)
    {
        //return $this->success($request->file('file'));
        $uploadFile = $request->getUploadedFiles();

        $fileKey = key($uploadFile); // 获取数组的第一个键（字段名）
        $uploadFile = reset($uploadFile); // 获取第一个文件

        $newTmpPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $uploadFile->getExtension();
        $uploadFile->moveTo($newTmpPath);
        $splFileInfo = new SplFileInfo($newTmpPath, '', '');
        return json_encode([
            'code'=>200,
            'errno'=>0,
            'data'=> $this->service->upload($splFileInfo, $uploadFile, 1)
        ]);
    }

    #[Post(
        path: '/v1/front-machine/i4',
        operationId: 'front-machine-i4',
        summary: '交警-2',
        tags: ['api'],
    )]
    public function i4(UploadRequest $request)
    {
        //return $this->success($request->file('file'));
        $uploadFile = $request->file('file');
        $newTmpPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $uploadFile->getExtension();
        $uploadFile->moveTo($newTmpPath);
        $splFileInfo = new SplFileInfo($newTmpPath, '', '');

        return json_encode([
            'code'=>200,
            'errno'=>0,
            'data'=> $this->service->upload($splFileInfo, $uploadFile, 1)
        ]);
    }
}

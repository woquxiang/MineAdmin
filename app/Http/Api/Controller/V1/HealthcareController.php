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
use App\Service\healthcare\HospitalVisitsService;
use App\Service\healthcare\MedicalExpensesService;
use App\Service\healthcare\PatientsService;
use App\Service\healthcare\TrafficIncidentsService;
use App\Service\PassportService;
use App\Service\rescuefund\RegionsService;
use App\Service\V1\AttachmentService;
use App\Service\V1\FrontmachineService;
use App\Service\V1\J123EventService;
use App\Service\V1\J123PeopleService;
use App\Service\XPassportService;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Redis\Redis;
use Hyperf\Stringable\Str;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use EasyWeChat\MiniApp\Application;
use Symfony\Component\Finder\SplFileInfo;


#[HyperfServer(name: 'http')]
#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
final class HealthcareController extends AbstractController
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
        protected readonly PatientsService $patientsService,
        protected readonly HospitalVisitsService $hospitalVisitsService,
        protected readonly MedicalExpensesService $medicalExpensesService,
        protected readonly Redis $redis,
    ) {}

    #[Post(
        path: '/v1/healthcare/list',
    )]
    public function page(RequestInterface $request)
    {
        $params = $this->getRequestData();

        // 将身份证和姓名加入到查询参数中
        $userInfo = $this->currentUser->user();

        $params['id_card_number'] = $userInfo['id_card_number'];
        $params['id_card_name'] = $userInfo['id_card_name'];

        return $this->success(
            $this->trafficIncidentsService->page(
                $params,
                $this->getCurrentPage(),
                $this->getPageSize()
            )
        );
    }

    #[Post(
        path: '/v1/healthcare/details',
    )]
    public function details(RequestInterface $request)
    {
        // 获取 POST 请求中的事故编号
        $id = $request->post('id');

        $userInfo = $this->currentUser->user();
        $userIdCard = $userInfo['id_card_number'];

        $patient =  $this->patientsService->getPatientByIdCard($userIdCard);
        $result = $this->trafficIncidentsService->getIncidentByAccidentAndPatientId((int) $id, (int) $patient['id']);

        //将 住院信息放到数据中
        $hospital_visits = $this->hospitalVisitsService->getVisitByAccidentAndPatientId($result['accident_id'],(int) $patient['id']);// 查询当事人信息
        $medical_expenses = $this->medicalExpensesService->getExpensesByVisitId((string) $hospital_visits['id']);

        $result->patient = $patient;
        $result->hospital_visits = $hospital_visits;
        $result->medical_expenses = $medical_expenses;

        return $this->success($result);
    }

}

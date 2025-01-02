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
use App\Service\injury\InjurySignatureService;
use App\Http\Admin\Request\injury\InjurySignatureRequest as Request;
use Hyperf\Validation\Annotation\Scene;


#[HyperfServer(name: 'http')]
#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
final class InjuryController extends AbstractController
{

    public function __construct(
        private readonly XPassportService $passportService,
        private readonly CurrentUser $currentUser,
        protected readonly Redis $redis,
        private readonly InjurySignatureService $injurySignatureService
    ) {}

    #[Post(
        path: '/v1/injury/signature/save',
    )]
    #[Scene(scene:'apiSignatureSave')]
    public function save(Request $request)
    {
        $params = $request->validated();

        //支持创建和修改
        $this->injurySignatureService->createOrUpdate($params);

        return $this->success();
    }
}

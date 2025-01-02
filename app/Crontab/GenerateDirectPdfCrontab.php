<?php
namespace App\Crontab;

use App\Client\RoadFund\RoadFundApplication;
use App\Repository\rescuefund\FilesRepository;
use App\Repository\rescuefund\RescueFundApplicationsRepository;
use App\Repository\rescuefund\RescueFundStatusRepository;
use App\Repository\rescuefund\RescueFundStatusRepository as Repository;
use App\Service\healthcare\TrafficIncidentsService;
use App\Service\rescuefund\RescueFundStatusService;
use Carbon\Carbon;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\Redis;
use Psr\Log\LoggerInterface;
use App\Service\emergency\TacceptJtdbService;
use App\Service\injury\InjuryClaimApplicationService;

class GenerateDirectPdfCrontab
{
//    #[Crontab(name: "foo1", rule: "*\/1 * * * * *", callback: "execute", memo: "这是一个示例的定时任务")]
//    public function execute()
//    {
//        print_r(date('Y-m-d H:i:s', time()));
//    }

    protected LoggerInterface $logger;
    protected $updateInterval = 3600; // 1 小时 (单位: 秒)


    public function __construct(
        protected readonly RescueFundStatusRepository $rescueFundStatusRepository,
        protected readonly RescueFundStatusService $rescueFundStatusService,
        protected readonly LoggerFactory $loggerFactory,
        protected readonly Redis $redis,
        protected readonly TrafficIncidentsService $trafficIncidentsService,
        protected readonly TacceptJtdbService $tacceptJtdbService,
        protected readonly InjuryClaimApplicationService $injuryClaimApplicationService


    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    //生成直赔pdf任务
    #[Crontab( name:"generateDirectPdf", rule: "*\/1 * * * * *", memo: "生成直赔pdf任务")]
    public function generateDirectPdf()
    {
        $applicationId = $this->redis->lPop('generateDirectCompensationPdf');
        if($applicationId){
            try{
                $this->injuryClaimApplicationService->generateDirectCompensationPdf($applicationId);
            }catch(\Throwable $e){
                print_r('PDF生成失败' . PHP_EOL);
                print_r($e->getMessage());
                print_r($e->getLine());
                //重新入队
                $this->redis->rPush('generateDirectCompensationPdf', $applicationId);
                $this->logger->error('生成直赔pdf任务失败', ['applicationId' => $applicationId, 'error' => $e->getMessage()]);
            }
        }
    } 
}

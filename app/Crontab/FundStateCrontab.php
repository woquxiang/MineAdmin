<?php
namespace App\Crontab;

use App\Client\RoadFund\RoadFundApplication;
use App\Repository\rescuefund\FilesRepository;
use App\Repository\rescuefund\RescueFundApplicationsRepository;
use App\Repository\rescuefund\RescueFundStatusRepository;
use App\Repository\rescuefund\RescueFundStatusRepository as Repository;
use App\Service\rescuefund\RescueFundStatusService;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class FundStateCrontab
{
//    #[Crontab(name: "foo1", rule: "*\/1 * * * * *", callback: "execute", memo: "这是一个示例的定时任务")]
//    public function execute()
//    {
//        print_r(date('Y-m-d H:i:s', time()));
//    }

    protected LoggerInterface $logger;

    public function __construct(
        protected readonly RescueFundStatusRepository $rescueFundStatusRepository,
        protected readonly RescueFundStatusService $rescueFundStatusService,
        protected readonly LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

//    #[Crontab( name:"updateState", rule: "*\/5 * * * * *", memo: "更新垫付接口信息")]
    #[Crontab(name:"updateState", rule: "* *\/2 * * *", memo: "更新垫付接口信息")]
    public function updateState()
    {
        // 获取超过4小时未同步的数据
        $outOfSyncData = $this->rescueFundStatusRepository->getOutOfSyncData();

        if ($outOfSyncData) {
            foreach ($outOfSyncData as $item) {
                $result = $this->rescueFundStatusService->syncDataByApplicationId($item['application_id']);
                if($result){
                    $str = "更新垫付数据{$item['application_id']}成功" . PHP_EOL;
                    $str .= "更新垫付数据{$item['application_id']}成功" . PHP_EOL;
                }else{
                    $str = "更新垫付数据{$item['application_id']}失败" . PHP_EOL;
                    $str .= "更新垫付数据{$item['application_id']}失败" . PHP_EOL;
                }
                $this->logger->info($str);
                //var_dump($var);
            }
        }else{
            $this->logger->info("没有要更新的垫付数据");
        }
    }
}
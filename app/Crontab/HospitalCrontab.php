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

class HospitalCrontab
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
        protected readonly TrafficIncidentsService $trafficIncidentsService

    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    #[Crontab( name:"updateAccidentsA", rule: "*\/30 * * * * *", memo: "同步患者数据")]
    public function updateAccidentsA()
    {
        // 从 Redis 中取出一个案件 ID
        $accidentId = $this->redis->lPop('accident_ids_list');

        if ($accidentId) {
            // 获取案件的上次更新时间
            $lastUpdateTime = $this->redis->hGet('accident_update_times', $accidentId);

            // 如果案件没有更新时间戳，或者距离上次更新时间超过 1 小时，才进行更新
            if (!$lastUpdateTime || Carbon::now()->diffInSeconds(Carbon::createFromTimestamp($lastUpdateTime)) > $this->updateInterval) {
                $result=  $this->trafficIncidentsService->findById($accidentId);
                if(!$result){
                    return ;
                }

                // 更新案件数据
                $this->trafficIncidentsService->updateAllDataByAccidentIdAndPatientId($result['accident_id'], (int) $result['parient_id']); // 假设 patientId = 1

                // 更新案件的更新时间戳
                $this->redis->hSet('accident_update_times', $accidentId, Carbon::now()->timestamp);

                // 处理完后，重新将案件 ID 放回队列的末尾，等待下一次处理
                $this->redis->rPush('accident_ids_list', $accidentId);
            } else {
                // 如果距离上次更新时间不超过 1 小时，跳过该案件
                $this->redis->rPush('accident_ids_list', $accidentId); // 将案件 ID 放回队列
            }

            //print_r($this->redis->lRange('accident_ids_list',0,-1));
        }
    }

    #[Crontab( name:"CreateOrUdpateAccidentsMain", rule: "*/5 * * * *", memo: "同步主体数据")]
    public function CreateOrUdpateAccidentsMain()
    {
        $this->trafficIncidentsService->queryAllFromHospital();
    }

    #[Crontab( name:"updateAccidentsB", rule: "*\/1 * * * * *", memo: "即时更新患者数据")]
    public function updateAccidentsB()
    {
        // 锁定 key 防止并发执行
        $lockKey = 'updateAccidentsB_task_lock';
        $isLocked = $this->redis->setnx($lockKey, 1); // 使用 setnx 设置锁，只有没有锁时才会成功

        if (!$isLocked) {
            // 如果已经有任务在执行，返回
            echo "Task is already running. Skipping this execution.\n";
            return;
        }

        // 设置锁过期时间，避免任务未正常结束时死锁
        $this->redis->expire($lockKey, 600); // 锁定 10 分钟，任务执行完后会自动释放

        // 从 Redis 队列中取出所有数据并执行任务
        $listKey = 'accident_ids_list2';

        // 获取 list 中所有的任务数据
        $dataList = $this->redis->lrange($listKey, 0, -1);

        if (!empty($dataList)) {
            // 执行任务逻辑
            foreach ($dataList as $data) {
                // 在此处理你的业务逻辑

//                echo "Processing task: $data\n";



                $result=  $this->trafficIncidentsService->findById($data);
                if(!$result) continue;

                // 更新案件数据
                $this->trafficIncidentsService->updateAllDataByAccidentIdAndPatientId($result['accident_id'], (int) $result['parient_id']); // 假设 patientId = 1

                //设置更新时间
                $this->redis->hSet('accident_update_times', $data, Carbon::now()->timestamp);
                // 任务处理完后移除数据
                $this->redis->lrem($listKey,  $data , 0); // 移除已处理的任务
            }
        }

        // 任务处理完毕，释放锁
        $this->redis->del($lockKey);
    }
}
<?php

namespace App\Service\healthcare;

use App\Client\Hospital\HospitalA;
use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\healthcare\TrafficIncidents;
use App\Repository\healthcare\PatientHospitalMappingRepository;
use App\Repository\healthcare\PatientsRepository;
use App\Service\IService;
use App\Repository\healthcare\TrafficIncidentsRepository as Repository;
use App\Repository\healthcare\HospitalVisitsRepository;
use App\Repository\healthcare\MedicalExpensesRepository; // Assuming you have a repository for traffic details
use Carbon\Carbon;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CachePut;
use Hyperf\Redis\Redis;


class TrafficIncidentsService extends IService
{
    public function __construct(
        protected readonly Repository $repository,
        protected readonly PatientsRepository $patientsRepository,
        protected readonly PatientHospitalMappingRepository $patientHospitalMappingRepository,
        protected readonly HospitalVisitsRepository $hospitalVisitsRepository,
        protected readonly MedicalExpensesRepository $medicalExpensesRepository, // Add TrafficDetailsRepository
        protected readonly HospitalA $hospitalA,
        protected readonly Redis $redis,
    ) {}

    public function page(array $params, int $page = 1, int $pageSize = 10): array
    {
        // 1. 获取数据
        $pageList = $this->repository->page($params, $page, $pageSize);
        // 2. 获取所有ID
        $needAddId = array_column($pageList['list'],'id');

        if(!empty($needAddId)){
            // 将数组转换为 Redis 可以处理的集合
            $tempSetName = 'temp_set_' . uniqid();
            $this->redis->sAdd($tempSetName, ...$needAddId);
            $this->redis->expire($tempSetName, 5);

            // 使用 SDIFF 命令获取差集
            $missingIds = $this->redis->sDiff($tempSetName, 'need_upd_acc_id');

            if(!empty($missingIds)){
                $this->redis->sAddArray('need_upd_acc_id',$missingIds);
                //定时任务列表
                $this->redis->lPush('accident_ids_list',...$missingIds);
            }
            //快速更新一次
            $this->redis->lPush('accident_ids_list2',...$needAddId);
        }

        return $pageList;
    }


    // 用于查询从医院A获取的患者数据并处理
    public function queryAllFromHospital()
    {
        $hospitalData =  $this->hospitalA->queryAllRegisterRecords();

        //获取缓存中，所有数据编号
        $allAccidentId = $this->getAllAccidentIds();

        foreach ($hospitalData as $data) {
            //存在事故号，就跳过
//            print_r($data['accid'] . PHP_EOL);
            if(in_array($data['accid'],$allAccidentId)) continue;
            print_r('开始工作' . $data['accid'] . PHP_EOL);

            // 判断身份证格式是否正确
            if (!$this->isValidIdCard($data['id_card'])) continue;

            // 处理患者信息
            $patient = $this->patientsRepository->getPatientByIdCard($data['id_card']);
            if (!$patient) {
                // 如果患者不存在，则新增
                $patient = $this->patientsRepository->create([
                    'name' => $data['name'],
                    'sex' => $data['sex'],
                    'id_card' => $data['id_card'],
                    'birthday' => Carbon::parse($data['birthday'])->toDateString(),
                ]);
            }

            // 判断是否存在患者与医院的关联
            $patientHospital = $this->patientHospitalMappingRepository->getPatientHospitalMapping($patient->id, 1); // 假设医院ID是1
            if (!$patientHospital) {
                $this->patientHospitalMappingRepository->create([
                    'patient_id' => $patient->id,
                    'hospital_id' => 1, // 假设医院ID是1
                    'hospital_patient_id' => $data['patientid'],
                ]);
            }

            // 处理事故数据
            $trafficIncident = $this->repository->getTrafficIncidentByAccidentId($data['accid']);
            $trafficIncidentData = [
                'accident_id' => $data['accid'],
                'patient_id' => $patient->id,
                'accident_time' => Carbon::parse($data['a_time'])->toDateTimeString(),
                'accident_location' => $data['a_address'],
                'allfee' => $data['allfee'],
                'prepay' => $data['prepay'],
            ];

            if (!$trafficIncident) {
                $this->repository->create($trafficIncidentData);
            }else{
                $trafficIncident->update($trafficIncidentData);
            }

            // 4. 同步就诊记录数据
            $hospitalVisit = $this->hospitalVisitsRepository->getVisitByAccidentIdAndPatientId($data['accid'], $patient->id);

            $traffic_medical = $this->hospitalA->queryMedicalRecordByAccid($data['accid']);

            $hospitalVisitData = [
                'visits_type' => '住院', // 默认住院，可以根据具体数据调整
                'accident_id' => $data['accid'],
                'patient_id' => $patient->id,
                'hospital_id' => 1, // 医院ID
                'diagnosis' => $traffic_medical['dname'] ?? '', // 假设诊断信息存储在 memo 中
                'section' => $traffic_medical['section'] ?? '', // 假设科室信息在 data['section']
                'sickarea' => $traffic_medical['sickarea'] ?? '', // 病区
                'bedno' => $traffic_medical['bedno'] ?? '', // 床位号
                'doctor' => $traffic_medical['doctor'] ?? '', // 医生
                'i_time' => !empty($traffic_medical['i_time']) ? Carbon::parse($traffic_medical['i_time'])->toDateTimeString() : null,
                'o_time' => !empty($traffic_medical['o_time']) ? Carbon::parse($traffic_medical['o_time'])->toDateTimeString() : null,
            ];
            if (!$hospitalVisit) {
                $this->hospitalVisitsRepository->create($hospitalVisitData);
            } else {
                $hospitalVisit->update($hospitalVisitData);
            }
        }

        $this->updateAccidentIdsCache();

        return $hospitalData; // 返回处理后的数据
    }

    /**
     * 更新单条数据，包含事故、患者、就诊记录和费用等所有数据
     */
    public function updateAllDataByAccidentIdAndPatientId(string $accidentId, int $patientId)
    {
        // 查询事故数据
        $data = $this->hospitalA->queryAllRegisterRecords($accidentId); // Assuming you can filter by accidentId

        foreach ($data as $record) {
            $patient = $this->patientsRepository->getPatientByIdCard($record['id_card']);

            // 更新事故信息
                $trafficIncident = $this->repository->getTrafficIncidentByAccidentId($accidentId);
                if ($trafficIncident) {
                    $trafficIncident->update([
                        'accident_time' => Carbon::parse($record['a_time'])->toDateTimeString(),
                        'accident_location' => $record['a_address'],
                        'allfee' => $record['allfee'],
                        'prepay' => $record['prepay'],
                    ]);
                }

            // 4. 同步就诊记录数据
            $hospitalVisit = $this->hospitalVisitsRepository->getVisitByAccidentIdAndPatientId($record['accid'], $patient->id);

            $traffic_medical = $this->hospitalA->queryMedicalRecordByAccid($record['accid']);

            $hospitalVisitData = [
                'visits_type' => '住院', // 默认住院，可以根据具体数据调整
                'accident_id' => $record['accid'],
                'patient_id' => $patient->id,
                'hospital_id' => 1, // 医院ID
                'diagnosis' => $traffic_medical['dname'] ?? '', // 假设诊断信息存储在 memo 中
                'section' => $traffic_medical['section'] ?? '', // 假设科室信息在 data['section']
                'sickarea' => $traffic_medical['sickarea'] ?? '', // 病区
                'bedno' => $traffic_medical['bedno'] ?? '', // 床位号
                'doctor' => $traffic_medical['doctor'] ?? '', // 医生
                'i_time' => !empty($traffic_medical['i_time']) ? Carbon::parse($traffic_medical['i_time'])->toDateTimeString() : null,
                'o_time' => !empty($traffic_medical['o_time']) ? Carbon::parse($traffic_medical['o_time'])->toDateTimeString() : null,
            ];

            if ($hospitalVisit) {
                $hospitalVisit->update($hospitalVisitData);
            }

                // 更新费用明细
            $medical_expenses_api_data = $this->hospitalA->queryAllDetailRecordsByAccid($accidentId);
            // 删除原先的费用明细
            $this->medicalExpensesRepository->deleteByVisitId($hospitalVisit->id);
// 构建批量插入数据
            $medical_expenses_data = [];
            foreach ($medical_expenses_api_data as $value) {
                $medical_expenses_data[] = [
                    'visit_id' => $hospitalVisit->id,
                    'expense_name' => $value['i_name'],
                    'price' => $value['price'],
                    'quantity' => $value['quantity'],
                ];
            }

// 批量插入新的费用明细
            if (!empty($medical_expenses_data)) {
                $this->medicalExpensesRepository->insertBatch($medical_expenses_data);
            }
        }
        return true;
    }

    /**
     * 验证身份证格式是否正确
     *
     * @param string $idCard
     * @return bool
     */
    private function isValidIdCard(string $idCard): bool
    {
        // 简单验证：检查长度和是否为数字组成
        if (preg_match('/^\d{15}$|^\d{17}[\dXx]$/', $idCard)) {
            return true;
        }
        return false;
    }

    /**
     * 根据 accident_id 和 patient_id 查找交通事故记录
     * 如果未找到，抛出 TrafficIncidentNotFoundException 异常
     */
    public function getIncidentByAccidentAndPatientId(int $id, int $patientId): ?TrafficIncidents
    {
        $incident = $this->repository->getIncidentByAccidentAndPatientId($id, $patientId);

        if (!$incident) {
            // 如果找不到记录，抛出异常
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '没有找到事故或住院信息');
        }

        return $incident;
    }

    /**
     * 获取所有事故编号
     */
    #[Cacheable(prefix: "traffic_incidents", ttl: -1, value: "_all")]
    public function getAllAccidentIds(): array
    {
        // 从仓库层获取所有事故编号
        return $this->repository->getAllAccidentIds();
    }

    /**
     * 更新缓存中的事故编号
     */
    #[CachePut(prefix: "traffic_incidents", ttl: -1, value: "_all")]
    public function updateAccidentIdsCache(): array
    {
        // 从仓库层重新获取所有事故编号
        return $this->repository->getAllAccidentIds();
    }
}

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

namespace App\Service\V1;

use App\Http\CurrentUser;
use App\Repository\J123\J123EventRepository;
use App\Repository\J123\J123PeopleRepository;
use App\Service\IService;

final class FrontmachineService extends IService
{
    public function __construct(
        protected readonly J123EventRepository $eventRepository,
        protected readonly J123PeopleRepository $peopleRepository,
    ) {}



    /**
     * 创建或更新事故记录和当事人记录
     *
     * @param array $info 事故基础信息
     * @param array $people 事故当事人信息
     */
    public function createOrUpdateAccidentRecords(array $info, array $people)
    {
        // 检查事故编号是否有效（非空、非空字符串）
        if (empty($info[0])) {
            // 如果事故编号为空，直接跳过或返回错误
            return; // 或者你可以抛出一个异常，或者返回一个错误提示
        }

        // 先检查事故是否存在
        $accident = $this->eventRepository->findOneByAccidentNumber($info[0]);

        // 如果事故记录不存在，创建一个新的事故记录
        if (!$accident) {
            $accident = [
                'accident_number' => $info[0],
                'event_date' => $info[1] ?? date("Y-m-d H:i"),
                'weather' => $info[2] ?? '',
                'location' => $info[3] ?? '',
                'accident_scenario' => $info[4] ?? '',
                'accident_type' => $info[5] ?? '',
                'data_source' => $info[6] ?? '',
                'handling_method' => $info[7] ?? '',
                'management_department' => $info[8] ?? '',
                'accident_status' => $info[9] ?? '',
            ];
            $this->eventRepository->create($accident);
        } else {
            // 如果事故记录已存在，进行更新
            $accident->update([
                'event_date' => $info[1] ?? $accident->event_date,
                'weather' => $info[2] ?? $accident->weather,
                'location' => $info[3] ?? $accident->location,
                'accident_scenario' => $info[4] ?? $accident->accident_scenario,
                'accident_type' => $info[5] ?? $accident->accident_type,
                'data_source' => $info[6] ?? $accident->data_source,
                'handling_method' => $info[7] ?? $accident->handling_method,
                'management_department' => $info[8] ?? $accident->management_department,
                'accident_status' => $info[9] ?? $accident->accident_status,
            ]);
        }

        // 处理事故当事人记录
        foreach ($people as $_people) {
            if (count($_people) == 5) { // 人
                $this->createOrUpdatePeople($info[0], $_people, 5);
            } elseif (count($_people) == 8) { // 车
                $this->createOrUpdatePeople($info[0], $_people, 8);
            }
        }
    }

    /**
     * 创建或更新当事人记录
     *
     * @param string $accidentNumber 事故编号
     * @param array $person 当事人信息
     * @param int $type 当事人类型（5 表示人，8 表示车）
     */
    private function createOrUpdatePeople(string $accidentNumber, array $person, int $type)
    {
        // 查找是否已存在当事人记录
        $personRecordModel = $this->peopleRepository->findOneByAccidentNumberAndName($accidentNumber, $person[0]);

        $personRecord = [
            'accident_number' => $accidentNumber,
            'name' => $person[0] ?? '',
            'id_number' => $person[1] ?? '',
            'vehicle_type' => $person[2] ?? '',
            'phone' => $person[3] ?? '',
        ];

        if ($type == 8) {
            // 如果是车的信息，添加车相关字段
            $personRecord['car_type'] = $person[4] ?? '';
            $personRecord['license_plate'] = $person[5] ?? '';
            $personRecord['insurance_company'] = $person[6] ?? '';
            $personRecord['responsibility'] = $person[7] ?? '';
        }else{//人
            $personRecord['responsibility'] = $person[4] ?? '';
        }

        if (!$personRecordModel) {
            // 如果不存在，则创建新记录
            $this->peopleRepository->create($personRecord);
        } else {
            // 如果当事人记录存在，进行更新
            unset($personRecord['accident_number']);
            $personRecordModel->update($personRecord);
        }
    }
}

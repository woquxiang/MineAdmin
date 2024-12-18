<?php

namespace App\Repository\healthcare;

use App\Model\healthcare\Patients;
use App\Repository\IRepository;
use App\Model\healthcare\TrafficIncidents as Model;
use Hyperf\Database\Model\Builder;


class TrafficIncidentsRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    /**
     * 根据 accident_id 和 patient_id 查找交通事故记录
     */
    public function getIncidentByAccidentAndPatientId(int $id, int $patientId): ?Model
    {
        return $this->getQuery()
            ->where('id', $id)
            ->where('patient_id', $patientId)
            ->first();
    }

    /**
     * 根据事故编号（accident_id）获取交通事故记录
     */
    public function getTrafficIncidentByAccidentId(string $accidentId): ?Model
    {
        return $this->model->newQuery()->where('accident_id', $accidentId)->first();
    }

    /**
     * 获取所有事故编号（accident_id）
     */
    public function getAllAccidentIds(): array
    {
        return $this->getQuery()->pluck('accident_id')->toArray();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (array_key_exists('id_card_number',$params) && array_key_exists('id_card_name',$params)) {
            $patient = Patients::where('id_card', $params['id_card_number'])
                ->where('name', $params['id_card_name'])
                ->first();

            if ($patient) {
                // 找到患者，添加条件限制 patient_id
                $query->where('patient_id', $patient->id);
            }else{
                // 如果没有找到患者，可以选择如何处理，或使用 null 来允许查询所有
                $query->where('patient_id', -1);
//                $query->whereNull('patient_id'); // 例：查找没有患者信息的记录，或者可以根据需求处理
            }
        }
                                                        
        if (isset($params['accident_id'])) {
            $query->where('accident_id', $params['accident_id']);
        }
                                                                    
        if (isset($params['patient_id'])) {
            $query->where('patient_id', $params['patient_id']);
        }
                                                                                                                        
        return $query;
    }
}

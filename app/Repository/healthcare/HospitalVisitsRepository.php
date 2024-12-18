<?php

namespace App\Repository\healthcare;

use App\Repository\IRepository;
use App\Model\healthcare\HospitalVisits as Model;
use Hyperf\Database\Model\Builder;


class HospitalVisitsRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    // 根据事故ID和患者ID获取就诊记录
    public function getVisitByAccidentIdAndPatientId(string $accidentId, int $patientId): ?Model
    {
        return $this->model->newQuery()
            ->where('accident_id', $accidentId)
            ->where('patient_id', $patientId)
            ->first(); // 返回匹配的第一条记录，如果没有则返回 null
    }


    public function handleSearch(Builder $query, array $params): Builder
    {
                                                        
        if (isset($params['visits_type'])) {
            $query->where('visits_type', $params['visits_type']);
        }
                                                                                                                                                                                                                                                
        return $query;
    }
}

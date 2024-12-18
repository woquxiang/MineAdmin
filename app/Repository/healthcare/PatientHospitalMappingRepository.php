<?php

namespace App\Repository\healthcare;

use App\Repository\IRepository;
use App\Model\healthcare\PatientHospitalMapping as Model;
use Hyperf\Database\Model\Builder;


class PatientHospitalMappingRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    public function getPatientHospitalMapping(int $patientId, int $hospitalId): ?Model
    {
        return $this->model->newQuery()
            ->where('patient_id', $patientId)
            ->where('hospital_id', $hospitalId)
            ->first();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                        
        if (isset($params['patient_id'])) {
            $query->where('patient_id', $params['patient_id']);
        }
                                                                                                                        
        return $query;
    }
}

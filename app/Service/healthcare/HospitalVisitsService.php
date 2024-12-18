<?php

namespace App\Service\healthcare;

use App\Model\healthcare\HospitalVisits;
use App\Service\IService;
use App\Repository\healthcare\HospitalVisitsRepository as Repository;



class HospitalVisitsService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}

    /**
     * 根据 accident_id 和 patient_id 获取就诊记录
     * 如果找不到，直接返回 null
     */
    public function getVisitByAccidentAndPatientId(string $accidentId, int $patientId): ?HospitalVisits
    {
        return $this->repository->getVisitByAccidentIdAndPatientId($accidentId, $patientId);
    }
}

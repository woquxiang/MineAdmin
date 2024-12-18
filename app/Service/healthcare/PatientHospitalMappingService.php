<?php

namespace App\Service\healthcare;

use App\Service\IService;
use App\Repository\healthcare\PatientHospitalMappingRepository as Repository;



class PatientHospitalMappingService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}
}

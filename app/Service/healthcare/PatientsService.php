<?php

namespace App\Service\healthcare;

use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\healthcare\Patients;
use App\Service\IService;
use App\Repository\healthcare\PatientsRepository as Repository;



class PatientsService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}

    /**
     * 根据身份证号获取患者信息
     * 如果未找到，抛出 PatientNotFoundException 异常
     */
    public function getPatientByIdCard(string $idCard): ?Patients
    {
        $patient = $this->repository->getPatientByIdCard($idCard);

        if (!$patient) {
            // 如果找不到患者，抛出异常
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '没有找到患者信息');
        }

        // 如果找到患者，返回患者信息
        return $patient;
    }
}

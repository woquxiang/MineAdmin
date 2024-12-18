<?php

namespace App\Service\healthcare;

use App\Service\IService;
use App\Repository\healthcare\MedicalExpensesRepository as Repository;



class MedicalExpensesService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}

    /**
     * 根据 visit_id 获取所有费用记录
     * 返回的结果是一个数组，如果没有费用记录则返回空数组
     */
    public function getExpensesByVisitId(string $visitId): array
    {
        return $this->repository->getExpensesByVisitId($visitId);
    }
}

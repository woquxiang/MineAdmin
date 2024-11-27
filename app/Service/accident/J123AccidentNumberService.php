<?php

namespace App\Service\accident;

use App\Service\IService;
use App\Repository\accident\J123AccidentNumberRepository as Repository;



class J123AccidentNumberService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}

    // 查询所有状态为1的事故编号
    public function getAccidentNumbersWithStatusOne(): array
    {
        return $this->repository->findAccidentNumbersByStatus();
    }
}

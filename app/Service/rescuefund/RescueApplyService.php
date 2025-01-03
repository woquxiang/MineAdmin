<?php

namespace App\Service\rescuefund;

use App\Service\IService;
use App\Repository\rescuefund\RescueApplyRepository as Repository;



class RescueApplyService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}
}

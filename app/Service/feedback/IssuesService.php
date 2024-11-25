<?php

namespace App\Service\feedback;

use App\Service\IService;
use App\Repository\feedback\IssuesRepository as Repository;



class IssuesService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}
}

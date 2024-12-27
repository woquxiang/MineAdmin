<?php

namespace App\Service\injury;

use App\Service\IService;
use App\Repository\injury\InjuryPartyInformationRepository as Repository;



class InjuryPartyInformationService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}
}

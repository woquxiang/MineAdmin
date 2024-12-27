<?php

namespace App\Repository\injury;

use App\Repository\IRepository;
use App\Model\injury\InjuryPartyInformation as Model;
use Hyperf\Database\Model\Builder;


class InjuryPartyInformationRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    //根据application_id查询
    public function getPartyByApplicationId(int $applicationId):array
    {
        return $this->getQuery()->where('application_id', $applicationId)->get()->toArray();
    }

    //删除
    public function deleteByApplicationId(int $applicationId):void
    {
        $this->getQuery()->where('application_id', $applicationId)->delete();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                                            
        if (isset($params['name'])) {
            $query->where('name', $params['name']);
        }
                                                                                                                                                                                                                                                                                                                                                                                            
        return $query;
    }
}

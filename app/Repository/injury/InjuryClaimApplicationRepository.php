<?php

namespace App\Repository\injury;

use App\Repository\IRepository;
use App\Model\injury\InjuryClaimApplication as Model;
use Hyperf\Database\Model\Builder;


class InjuryClaimApplicationRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    //根据id获取人伤直赔记录 带关联查询
    public function getInjuryClaimApplicationByIdWithParty(int $id):Model
    {
        return $this->getQuery()->where('id', $id)->with('party_information')->firstOrFail();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                        
        if (isset($params['claim_code'])) {
            $query->where('claim_code', $params['claim_code']);
        }
                                                                                                                                                                                                                                                
        return $query;
    }

}

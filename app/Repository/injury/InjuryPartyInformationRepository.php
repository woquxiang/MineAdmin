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

    //通过直赔ID 查询直赔信息 并查询出人伤直赔记录 带关联查询
    public function findByDirectCompensationId($directIndemnityId)
    {
        return $this->getQuery()->where('direct_compensation_id', $directIndemnityId)->with('application')->first();
    }

    //删除
    public function deleteByApplicationId(int $applicationId):void
    {
        $this->getQuery()->where('application_id', $applicationId)->delete();
    }

    //获取指定日期当天伤者列表  hospital_id 和 time 是必传参数  
    public function getTodayInjuredList($hospitalId, $time)
    {
        return $this->getQuery()->where('hospital_id', $hospitalId)
        ->whereDate('created_at', $time)
        ->with('application')
        ->orderBy('created_at', 'desc')
        ->get()->toArray();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {                                                                            
        if (isset($params['name'])) {
            $query->where('name', $params['name']);
        }
                                                                                                                                                                                                                                                                                                                                                                                            
        return $query;
    }
}

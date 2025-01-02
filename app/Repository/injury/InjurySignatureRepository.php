<?php

namespace App\Repository\injury;

use App\Repository\IRepository;
use App\Model\injury\InjurySignature as Model;
use Hyperf\Database\Model\Builder;


class InjurySignatureRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    //创建或修改
    public function createOrUpdate(array $data)
    {
        $this->model->upsert($data, ['direct_compensation_id']);
    }

    //根据直赔ID和姓名查询
    public function findByDirectCompensationIdAndName(string $directCompensationId, string $name): ?Model
    {
        return $this->getQuery()->where('direct_compensation_id', $directCompensationId)->where('name', $name)->first();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                        
        if (isset($params['application_id'])) {
            $query->where('application_id', $params['application_id']);
        }
                                                                    
        if (isset($params['direct_compensation_id'])) {
            $query->where('direct_compensation_id', $params['direct_compensation_id']);
        }
                                                                                                                                                                                    
        return $query;
    }
}

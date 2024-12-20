<?php

namespace App\Repository\emergency;

use App\Repository\IRepository;
use App\Model\emergency\TacceptJtdb as Model;
use Hyperf\Database\Model\Builder;


class TacceptJtdbRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    //根据字段sjbm in查询存在
    public function getBySjbm(array $sjbm): array
    {
        return $this->getQuery()->whereIn('sjbm', $sjbm)->get()->toArray();
    }

    //批量新增
    public function insertBatch(array $data): void
    {
        $this->model->insert($data);
    }

    //批量更新
    public function updateBatch(array $data): void
    {
        $this->model->upsert($data, ['sjbm']);
    }

    //通过sjbm字段更新
    public function updateBySjbm(array $data): void
    {
        $this->getQuery()->where('sjbm', $data['sjbm'])->update($data);
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                        
        if (isset($params['sjbm'])) {
            $query->where('sjbm', $params['sjbm']);
        }
                                                                                                                                
        if (isset($params['hzxm'])) {
            $query->where('hzxm', $params['hzxm']);
        }
                                                                                                                                                                                                                                                                                                            
        return $query;
    }
}

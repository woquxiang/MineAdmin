<?php

namespace App\Repository\accident;

use App\Repository\IRepository;
use App\Model\accident\J123AccidentNumber as Model;
use Hyperf\Database\Model\Builder;


class J123AccidentNumberRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                        
        if (isset($params['accident_number'])) {
            $query->where('accident_number', $params['accident_number']);
        }
                                                                    
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }
                                                                                                                        
        return $query;
    }

    // 新增：查询所有状态为1的事故编号
    public function findAccidentNumbersByStatus(int $status = 1): array
    {
        return $this->model->newQuery()
            ->where('status', $status)
            ->pluck('accident_number')
            ->toArray();  // 返回一个包含事故编号的数组
    }
}

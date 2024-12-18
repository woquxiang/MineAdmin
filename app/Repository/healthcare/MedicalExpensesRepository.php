<?php

namespace App\Repository\healthcare;

use App\Repository\IRepository;
use App\Model\healthcare\MedicalExpenses as Model;
use Hyperf\Database\Model\Builder;


class MedicalExpensesRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    // In your TrafficDetailsRepository
    public function deleteByVisitId(string $visit_id)
    {
        return $this->getQuery()
//            ->where('accident_id', $accidentId)
            ->where('visit_id', $visit_id)
            ->delete();
    }

    /**
     * 根据 visit_id 获取所有费用记录
     */
    public function getExpensesByVisitId(string $visitId): array
    {
        return $this->getQuery()
            ->where('visit_id', $visitId)
            ->get() // 获取所有匹配的记录
            ->toArray(); // 转换为数组，方便返回
    }

    // In your TrafficDetailsRepository
    public function insertBatch(array $data)
    {
        return $this->model->insert($data);  // For Laravel's Eloquent ORM
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                        
        if (isset($params['visit_id'])) {
            $query->where('visit_id', $params['visit_id']);
        }
                                                                                                    
        return $query;
    }
}

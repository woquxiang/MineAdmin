<?php

namespace App\Repository\rescuefund;

use App\Repository\IRepository;
use App\Model\rescuefund\RescueApply as Model;
use Hyperf\Database\Model\Builder;


class RescueApplyRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
                                    
        if (isset($params['id'])) {
            $query->where('id', $params['id']);
        }
                                                                    
        if (isset($params['application_id'])) {
            $query->where('application_id', $params['application_id']);
        }
                                                                    
        if (isset($params['created_at'])) {
            $query->where('created_at', $params['created_at']);
        }
                                                                    
        if (isset($params['updated_at'])) {
            $query->where('updated_at', $params['updated_at']);
        }
                                                                    
        if (isset($params['created_by'])) {
            $query->where('created_by', $params['created_by']);
        }
                                                                    
        if (isset($params['updated_by'])) {
            $query->where('updated_by', $params['updated_by']);
        }
                                                                    
        if (isset($params['acceptPoint'])) {
            $query->where('acceptPoint', $params['acceptPoint']);
        }
                                                                    
        if (isset($params['accident_date'])) {
            $query->where('accident_date', $params['accident_date']);
        }
                                                                    
        if (isset($params['road'])) {
            $query->where('road', $params['road']);
        }
                                                                    
        if (isset($params['injured'])) {
            $query->where('injured', $params['injured']);
        }
                                                                    
        if (isset($params['type'])) {
            $query->where('type', $params['type']);
        }
                                                                    
        if (isset($params['reason'])) {
            $query->where('reason', $params['reason']);
        }
                                                                    
        if (isset($params['desc'])) {
            $query->where('desc', $params['desc']);
        }
                                                                    
        if (isset($params['relation_name'])) {
            $query->where('relation_name', $params['relation_name']);
        }
                                                                    
        if (isset($params['relation_phone'])) {
            $query->where('relation_phone', $params['relation_phone']);
        }
                                                                    
        if (isset($params['date'])) {
            $query->where('date', $params['date']);
        }
                                        
        return $query;
    }
}

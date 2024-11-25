<?php

namespace App\Repository\feedback;

use App\Repository\IRepository;
use App\Model\feedback\Issues as Model;
use Hyperf\Database\Model\Builder;


class IssuesRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                        
        if (isset($params['content'])) {
            $query->where('content', 'like', '%'.$params['content'].'%');
        }
                                                                    
        if (isset($params['contact_info'])) {
            $query->where('contact_info', 'like', '%'.$params['contact_info'].'%');
        }
                                                                                                                        
        return $query;
    }
}

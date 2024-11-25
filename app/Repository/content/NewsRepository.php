<?php

namespace App\Repository\content;

use App\Repository\IRepository;
use App\Model\content\News as Model;
use Hyperf\Database\Model\Builder;


class NewsRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                        
        if (isset($params['title'])) {
            $query->where('title', 'like', '%'.$params['title'].'%');
        }
                                                                    
        if (isset($params['short_description'])) {
            $query->where('short_description', 'like', '%'.$params['short_description'].'%');
        }
                                                                    
        if (isset($params['content'])) {
            $query->where('content', 'like', '%'.$params['content'].'%');
        }
                                                                                                                                                                        
        if (isset($params['author'])) {
            $query->where('author', $params['author']);
        }
                                        
        return $query;
    }
}

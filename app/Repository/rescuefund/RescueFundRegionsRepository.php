<?php

namespace App\Repository\rescuefund;

use App\Repository\IRepository;
use App\Model\rescuefund\RescueFundRegions as Model;
use Hyperf\Collection\Arr;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;


class RescueFundRegionsRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    public function list(array $params = []): \Hyperf\Collection\Collection
    {
        return $this->perQuery($this->getQuery($params), $params)
            ->select(['id','parent_id','region_name'])
            ->orderBy('parent_id')->get();
    }

    public function allTree(): Collection
    {
        return $this->model
            ->newQuery()
            ->where('parent_id', 0)
            ->with('children')
            ->get();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(Arr::get($params, 'children'), static function (Builder $query) {
                $query->with('children');
            })
            ->when(Arr::has($params, 'parent_id'), static function (Builder $query) use ($params) {
                $query->where('parent_id', Arr::get($params, 'parent_id'));
            });
    }

}

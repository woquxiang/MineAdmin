<?php

namespace App\Repository\rescuefund;

use App\Repository\IRepository;
use App\Model\rescuefund\RescueFundStatus as Model;
use Carbon\Carbon;
use Hyperf\Database\Model\Builder;


class RescueFundStatusRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                                            
        if (isset($params['sqxx_id'])) {
            $query->where('sqxx_id', $params['sqxx_id']);
        }
        if (isset($params['application_id'])) {
            $query->where('application_id', $params['application_id']);
        }
                                                                                                                                                                                                        
        return $query;
    }

    /**
     * 通过 sqxx_id 查找唯一的 RescueFundStatus 记录
     *
     * @param string $sqxxId
     * @return Model|null
     */
    public function findOneBySqxxId(string $sqxxId): ?Model
    {
        return $this->model
            ->newQuery()
            ->where('sqxx_id', $sqxxId)->first();
    }


    /**
     * 获取超过4小时未同步的数据
     */
    public function getOutOfSyncData():array
    {
        // 获取当前时间并减去4小时
        $fourHoursAgo = Carbon::now()->subHours(4);

        // 查询更新超过4小时的数据
        return $this->model
            ->newQuery()
            ->where('updated_at', '<', $fourHoursAgo)->get()->toArray();;
    }

}

<?php

namespace App\Service\rescuefund;

use App\Service\IService;
use App\Repository\rescuefund\RescueFundRegionsRepository as Repository;
use Hyperf\Collection\Collection;
use Hyperf\Cache\Annotation\Cacheable;


class RegionsService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}

    //#[Cacheable(prefix: "userBook", ttl: -1, value: "_#abccc")]
    public function getListArray(array $paras): ?array
    {
        // 提取 'age' 字段进行排序
        $list = $this->repository->list($paras)->toArray();
        $ages = array_column($list, 'id');
        array_multisort($ages, SORT_ASC, $list);

        return $list;
    }
}

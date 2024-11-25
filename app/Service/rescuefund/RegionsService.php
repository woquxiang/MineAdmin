<?php

namespace App\Service\rescuefund;

use App\Repository\rescuefund\RescueFundApplicationsRepository;
use App\Service\IService;
use App\Repository\rescuefund\RescueFundRegionsRepository as Repository;
use Hyperf\Collection\Collection;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CachePut;


class RegionsService extends IService
{
    public function __construct(
        protected readonly Repository $repository,
        protected readonly RescueFundApplicationsRepository $rescueFundApplicationsRepository
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

    #[Cacheable(prefix: "region", ttl: -1, value: "_#{regionCode}")]
//    #[CachePut(prefix: "region", ttl: -1, value: "_#{regionCode}")]
    public function getRegionByCode(mixed $regionCode): ?array
    {
        $region =  $this->repository->findById($regionCode);

        // 如果没有找到，返回 null
        if (!$region) {
            return ['region_name'=>''];
        }

        return $region->toArray();
    }


    /**
     * 根据ID获取省市区名称并返回
     * 组合省市区名称
     */
    public function getRegionNamesByFundId(int $id): array
    {
        $data = $this->rescueFundApplicationsRepository->findById($id);

        if (!$data) {
            return []; // 如果没有找到数据，返回空数组
        }

        // 获取对应的省市区名称
        $sgProv = $this->getRegionByCode($data->sg_prov);
        $sgCity = $this->getRegionByCode($data->sg_city);
        $sgArea = $this->getRegionByCode($data->sg_area);

        return [
            'sg_prov' => $sgProv['region_name'] ?? '',
            'sg_city' => $sgCity['region_name'] ?? '',
            'sg_area' => $sgArea['region_name'] ?? '',
        ];
    }
}

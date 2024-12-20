<?php

namespace App\Service\emergency;

use App\Service\IService;
use App\Repository\emergency\TacceptJtdbRepository as Repository;
use App\Client\Hospital\Hospital120;
use Carbon\Carbon;


class TacceptJtdbService extends IService
{
    public function __construct(
        protected readonly Repository $repository,
        protected readonly Hospital120 $hospital120
    ) {}

    //通过Hospital120 查询TACCEPT_JtDB 根据 SJBM字段倒叙 查询前500条数据，然后data数组分块处理每次100条，存在就放到需要更新的数组中，不存在就放到需要新增的数组中最后批量更新和新增
    public function updateTacceptJtdb(): void
    {
        $data = $this->hospital120->queryTacceptJtdb();
        $data = array_chunk($data, 100);
        $updateData = [];
        $insertData = [];

        //print_r($data);

        foreach($data as &$items){
            //把items里面所有的数组 把key转小写
            $items = array_map(function($item){
                return array_change_key_case($item, CASE_LOWER);
            }, $items);

            //把items里面所有的数组 的gxsj转成日期时间
            $items = array_map(function($item){
                $item['gxsj'] = Carbon::parse($item['gxsj'])->format('Y-m-d H:i:s');
                return $item;
            }, $items);

            $all_SJBM = array_column($items,'sjbm');


            $exist_SJBM = $this->repository->getBySjbm($all_SJBM);


            //存在的编号
            $exist_SJBM = array_column($exist_SJBM,'sjbm'); 

            //需要新增的编号
            $insertData = array_diff($all_SJBM,$exist_SJBM);


            //有了编号，通过items把数据收集成二维数组
            $insertData = array_filter($items,function($item) use ($insertData){
                return in_array($item['sjbm'],$insertData);
            });
            //需要更新的编号
            $updateData = array_intersect($all_SJBM,$exist_SJBM);
            //需要更新的数据
            $updateData = array_filter($items,function($item) use ($updateData){
                return in_array($item['sjbm'],$updateData);
            });
        }

        //判断不存在的数据 批量新增
        if(!empty($insertData)){
            print_r('buweikong1' .json_encode($insertData));
            $this->repository->insertBatch($insertData);
        }
        /**
        //判断存在的数据 逐个更新
        if(!empty($updateData)){
            foreach($updateData as $item){
                //通过sjbm字段更新
                $this->repository->updateBySjbm($item);
            }
        }
         * */
        //判断存在的数据 批量更新
        if(!empty($updateData)){
            $this->repository->updateBatch($updateData);
        }
    }
}

<?php

namespace App\Service\injury;

use App\Service\IService;
use App\Repository\injury\InjuryPartyInformationRepository as Repository;
use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Repository\injury\InjuryClaimApplicationRepository;

class InjuryPartyInformationService extends IService
{
    public function __construct(
        protected readonly Repository $repository,
        protected readonly InjuryClaimApplicationRepository $injuryClaimApplicationRepository
    ) {}

    //通过直赔ID 查询直赔信息
    public function findByDirectCompensationId($directIndemnityId)
    {
        $result = $this->repository->findByDirectCompensationId($directIndemnityId);
        if(!$result){
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '没有找到记录');
        }else{
            return $this->handleData($result);
        }
    }

    //医院当天所有伤者列表
    public function getTodayInjuredList($hospitalId, $time)
    {
        $list =  $this->repository->getTodayInjuredList($hospitalId, $time);
        $data = [];
        foreach($list as $item){
            $data[] = $this->handleData($item);
        }
        return $data;
    }

    //写一个处理数据的方法 只要支持单条数据
    public function handleData($data)
    {
        //判断是集合还是单条数据
        if(is_array($data)){
            $result = $data;
        }else{
            $result = $data->toArray();
        }

        //根据身份证号提取出年龄
        $idCard =  $result['id_number'];
        $age = (int) date('Y') - (int) substr($idCard, 6, 4);

        $data = [
            'injuredId' => $result['direct_compensation_id'],
            'injuredName' => $result['name'],
            'injuredGender' => $result['gender'],
            'injuredAge' => $age,
            'injuredCardId' => $result['id_number'],
            'injuredPhone' => $result['phone'],
            'injuredAddress' => $result['address'],
            //交通方式
            'injuredTrafficMethod' => $result['transportation_method'],
            'arrivedTime' => date("Y-m-d"),
            //事故相关信息
            'accidentAddress' => $result['application']['accident_location'],
            'accidentTime' => $result['application']['accident_time'],
        ];
        return $data;
    }
}
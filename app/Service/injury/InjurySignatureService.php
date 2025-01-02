<?php

namespace App\Service\injury;

use App\Service\IService;
use App\Repository\injury\InjurySignatureRepository as Repository;
use App\Repository\injury\InjuryPartyInformationRepository;
use App\Exception\BusinessException;
use App\Http\Common\ResultCode;

class InjurySignatureService extends IService
{
    public function __construct(
        protected readonly Repository $repository,
        protected readonly InjuryPartyInformationRepository $injuryPartyInformationRepository
    ) {}

    /**
     * 创建或更新
     * @param array $data
     * @return void
     */
    public function createOrUpdate(array $data)
    {
        //根据 direct_compensation_id 获取人伤案件
        $injuryParty = $this->injuryPartyInformationRepository->findByDirectCompensationId($data['direct_compensation_id']);

        if(!$injuryParty){
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '人伤案件不存在');
        }

        $data['application_id'] = $injuryParty['application_id'];   
        $data['name'] = $injuryParty['name'];

        //判断签名是否是base64数据
        if(base64_encode(base64_decode($data['signature_data'], true)) === $data['signature_data']){
            $data['signature_data'] = base64_encode($data['signature_data']);
        }else{
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '签名格式错误');
        }

        //判断直赔ID和姓名是否存在
        $signature = $this->repository->findByDirectCompensationIdAndName($data['direct_compensation_id'],$data['name']);
        //如果存在，则更新数据
        if($signature){
            $data['id'] = $signature['id'];
            $this->repository->updateById($data['id'],$data);
        }else{
            $this->repository->create($data);
        }

        return true;
        //用内置的创建或者修改的方法
        //$this->repository->createOrUpdate($data);
    }

}

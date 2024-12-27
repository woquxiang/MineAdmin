<?php

namespace App\Service\injury;

use App\Service\IService;
use App\Repository\injury\InjuryClaimApplicationRepository as Repository;
use App\Repository\injury\InjuryPartyInformationRepository;
use Hyperf\DbConnection\Db;
use App\Exception\BusinessException;
use App\Http\Common\ResultCode;


class InjuryClaimApplicationService extends IService
{
    public function __construct(
        protected readonly Repository $repository,
        //导入当事人仓库层
        protected readonly InjuryPartyInformationRepository $partyRepository
    ) {}

    
    public function create(array $data):mixed
    {
        //创建主表数据
        //直赔编号随机数

        //增加事务
        Db::beginTransaction();
        try{
            $data['claim_code'] = date('ymdHi'). mt_rand(100,999) . mt_rand(100,999);

            $application = $this->repository->create($data);
    
            //循环创建当事人数据
            foreach($data['party_information'] as $party){
                $party['application_id'] = $application->id;
                $this->partyRepository->create($party);
            }

        } catch(\Throwable $ex){
            Db::rollBack();
            print_r($ex->getMessage());
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '创建失败');
        }

        //提交事务      
        Db::commit();

        return $application;
    }

    //详情
    public function details(int $id):mixed
    {
        return $this->repository->getInjuryClaimApplicationByIdWithParty($id);
    }

    //

    //更新
    public function updateById(mixed $id, array $data):mixed
    {
        //增加事务
        Db::beginTransaction();
        try {
            // 更新主表数据
            unset($data['claim_code']);
            $this->repository->updateById($id, $data);
    
            // 获取目前所有当事人
            $existingParties = $this->partyRepository->getPartyByApplicationId($id);
            $existingPartyIds = array_column($existingParties, 'id');
            
            // 记录新数据中的ID，用于后续判断需要删除的记录
            $updatedPartyIds = [];
            
            // 遍历新的当事人数据
            foreach($data['party_information'] as $party) {
                if (isset($party['id'])) {
                    // 有ID，更新已存在的记录
                    $this->partyRepository->updateById($party['id'], $party);
                    $updatedPartyIds[] = $party['id'];
                } else {
                    // 没有ID，创建新记录
                    $party['application_id'] = $id;
                    $newParty = $this->partyRepository->create($party);
                    $updatedPartyIds[] = $newParty->id;
                }
            }
            
            // 找出需要删除的记录（在原数据中存在但新数据中不存在的记录）
            $partyIdsToDelete = array_diff($existingPartyIds, $updatedPartyIds);
            if (!empty($partyIdsToDelete)) {
                // 循环删除
                foreach($partyIdsToDelete as $did){
                    $this->partyRepository->deleteById($did);
                }
            }
    
        } catch(\Throwable $ex) {
            Db::rollBack();
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '更新失败');
        }
    
        //提交事务      
        Db::commit();
    
        return $this->repository->getInjuryClaimApplicationByIdWithParty($id);
    }
}

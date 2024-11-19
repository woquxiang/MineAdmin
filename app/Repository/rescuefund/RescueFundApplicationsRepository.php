<?php

namespace App\Repository\rescuefund;

use App\Repository\IRepository;
use App\Model\rescuefund\RescueFundApplications as Model;
use Hyperf\Database\Model\Builder;


class RescueFundApplicationsRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}


    /**
     * 根据 ID 和 created_by 获取详情
     * @param int $id
     * @param int $createdBy
     * @return array|null
     */
    public function getDetailByIdAndCreatedBy(int $id, int $createdBy): ?Model
    {
        return $this->model->newQuery()
            ->where('id', $id)
            ->where('created_by', $createdBy)
            ->first();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
                                    
        if (isset($params['id'])) {
            $query->where('id', $params['id']);
        }
                                                                    
        if (isset($params['apply_fee_type'])) {
            $query->where('apply_fee_type', $params['apply_fee_type']);
        }
                                                                    
        if (isset($params['sg_time'])) {
            $query->where('sg_time', $params['sg_time']);
        }
                                                                    
        if (isset($params['sg_prov'])) {
            $query->where('sg_prov', $params['sg_prov']);
        }
                                                                    
        if (isset($params['sg_city'])) {
            $query->where('sg_city', $params['sg_city']);
        }
                                                                    
        if (isset($params['sg_area'])) {
            $query->where('sg_area', $params['sg_area']);
        }
                                                                    
        if (isset($params['sg_address'])) {
            $query->where('sg_address', $params['sg_address']);
        }
                                                                    
        if (isset($params['shr_name'])) {
            $query->where('shr_name', $params['shr_name']);
        }
                                                                    
        if (isset($params['shr_phone'])) {
            $query->where('shr_phone', $params['shr_phone']);
        }
                                                                    
        if (isset($params['shr_credentials_type'])) {
            $query->where('shr_credentials_type', $params['shr_credentials_type']);
        }
                                                                    
        if (isset($params['shr_credentials_code'])) {
            $query->where('shr_credentials_code', $params['shr_credentials_code']);
        }
                                                                    
        if (isset($params['identity_card_address'])) {
            $query->where('identity_card_address', $params['identity_card_address']);
        }
                                                                    
        if (isset($params['current_residence_address'])) {
            $query->where('current_residence_address', $params['current_residence_address']);
        }
                                                                    
        if (isset($params['sqjbr_name'])) {
            $query->where('sqjbr_name', $params['sqjbr_name']);
        }
                                                                    
        if (isset($params['sqjbr_phone'])) {
            $query->where('sqjbr_phone', $params['sqjbr_phone']);
        }
                                                                    
        if (isset($params['sqjbr_credentials_type'])) {
            $query->where('sqjbr_credentials_type', $params['sqjbr_credentials_type']);
        }
                                                                    
        if (isset($params['sqjbr_credentials_code'])) {
            $query->where('sqjbr_credentials_code', $params['sqjbr_credentials_code']);
        }
                                                                    
        if (isset($params['shr_relationship_type'])) {
            $query->where('shr_relationship_type', $params['shr_relationship_type']);
        }
                                                                    
        if (isset($params['relatives_phone'])) {
            $query->where('relatives_phone', $params['relatives_phone']);
        }
                                                                    
        if (isset($params['is_people'])) {
            $query->where('is_people', $params['is_people']);
        }
                                                                    
        if (isset($params['ent_name'])) {
            $query->where('ent_name', $params['ent_name']);
        }
                                                                    
        if (isset($params['channel_type'])) {
            $query->where('channel_type', $params['channel_type']);
        }

        if (isset($params['created_by'])) {
            $query->where('created_by', $params['created_by']);
        }

        if (isset($params['is_approved'])) {
            $query->where('is_approved', $params['is_approved']);
        }


        return $query;
    }
}

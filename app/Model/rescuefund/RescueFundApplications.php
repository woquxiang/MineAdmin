<?php

namespace App\Model\rescuefund;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class rescue_fund_applications
* @property string $id 
* @property string $apply_fee_type 
* @property string $sg_time 
* @property string $sg_prov 
* @property string $sg_city 
* @property string $sg_area 
* @property string $sg_address 
* @property string $shr_name 
* @property string $shr_phone 
* @property string $shr_credentials_type 
* @property string $shr_credentials_code 
* @property string $identity_card_address 
* @property string $current_residence_address 
* @property string $sqjbr_name 
* @property string $sqjbr_phone 
* @property string $sqjbr_credentials_type 
* @property string $sqjbr_credentials_code 
* @property string $shr_relationship_type 
* @property string $relatives_phone 
* @property string $is_people 
* @property string $ent_name 
* @property string $channel_type 
* @property string $created_at 
* @property string $updated_at 
*/
class RescueFundApplications extends MineModel
{
    use SoftDeletes;

    protected ?string $table = 'rescue_fund_applications';

    protected array $fillable = ['id','apply_fee_type','sg_time','sg_prov','sg_city','sg_area','sg_address','shr_name','shr_phone','shr_credentials_type','shr_credentials_code','identity_card_address','current_residence_address','sqjbr_name','sqjbr_phone','sqjbr_credentials_type','sqjbr_credentials_code','shr_relationship_type','relatives_phone','is_people','ent_name','channel_type','created_at','updated_at','created_by','updated_by','is_approved'];

    protected array $casts = ['id' => 'string','apply_fee_type' => 'string','sg_time' => 'string','sg_prov' => 'string','sg_city' => 'string','sg_area' => 'string','sg_address' => 'string','shr_name' => 'string','shr_phone' => 'string','shr_credentials_type' => 'string','shr_credentials_code' => 'string','identity_card_address' => 'string','current_residence_address' => 'string','sqjbr_name' => 'string','sqjbr_phone' => 'string','sqjbr_credentials_type' => 'string','sqjbr_credentials_code' => 'string','shr_relationship_type' => 'string','relatives_phone' => 'string','is_people' => 'string','ent_name' => 'string','channel_type' => 'string','created_at' => 'string','updated_at' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'is_approved' => 'integer',
        ];
}
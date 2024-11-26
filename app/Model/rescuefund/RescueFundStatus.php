<?php

namespace App\Model\rescuefund;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class rescue_fund_status
* @property string $id 
* @property string $application_id 
* @property string $sqxx_id 
* @property string $wechat_sqxx_state 
* @property string $approve_user_name 
* @property string $give_money_time 
* @property string $apply_fee_type 
* @property string $adjustment_money 
* @property string $wechat_approve_state 
* @property string $return_reason 
* @property string $file_list 
*/
class RescueFundStatus extends MineModel
{
//    public bool $timestamps = false;

    protected ?string $table = 'rescue_fund_status';

    protected array $fillable = ['id','application_id','sqxx_id','wechat_sqxx_state','approve_user_name','give_money_time','apply_fee_type','adjustment_money','wechat_approve_state','return_reason','file_list','created_at','updated_at','created_by','updated_by'];

    protected array $casts = ['id' => 'string','application_id' => 'string','sqxx_id' => 'string','wechat_sqxx_state' => 'string','approve_user_name' => 'string','give_money_time' => 'string','apply_fee_type' => 'string','adjustment_money' => 'string','wechat_approve_state' => 'string','return_reason' => 'string','file_list' => 'array',];
}
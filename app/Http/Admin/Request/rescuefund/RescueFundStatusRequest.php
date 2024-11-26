<?php

namespace App\Http\Admin\Request\rescuefund;

use Hyperf\Validation\Request\FormRequest;


class RescueFundStatusRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                                            
            'application_id' => 'required',                            
            'sqxx_id' => 'required',                            
            'wechat_sqxx_state' => 'required',                            
            'approve_user_name' => 'required',                            
            'give_money_time' => 'required',                            
            'apply_fee_type' => 'required',                            
            'adjustment_money' => 'required',                            
            'wechat_approve_state' => 'required',                            
            'return_reason' => 'required',                            
            'file_list' => 'required',            
        ];
    }

    public function attributes(): array
    {
        return ['id' => '主键ID','application_id' => '关联的申请ID（主表的主键）','sqxx_id' => '提交的ID','wechat_sqxx_state' => '案件状态','approve_user_name' => '审核人用户账号','give_money_time' => '支付时间','apply_fee_type' => '费用类型','adjustment_money' => '垫付金额','wechat_approve_state' => '审核状态','return_reason' => '退回原因','file_list' => '银行回单列表（JSON格式）',];
    }

}
<?php

namespace App\Http\Admin\Request\rescuefund;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;


class RescueFundApplicationsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected array $scenes = [
//        'backendCreate' => ['apply_fee_type','sg_time','sg_address','shr_name','shr_phone','shr_credentials_type','shr_credentials_code'],
//        'backendCreate' => ['apply_fee_type'],
//        'frontendCreate' => ['sg_time','sg_address','shr_name','shr_phone','shr_credentials_type','shr_credentials_code'],
//        'frontendCreate' => ['sg_time'],
    ];

    public function rules(): array
    {
        return [                                            
            'apply_fee_type' => ['required', Rule::in(['2010001', '2010002' , '2010003'])],
            'sg_time' => 'required|date',  // 使用 `date` 规则验证时间格式
            'sg_prov'=>'required',
            'sg_city'=>'required',
            'sg_area'=>'required',
            'sg_address' => 'required',
            'shr_name' => 'required',                            
            'shr_phone' => 'required',                            
            'shr_credentials_type' => ['required', Rule::in(['2012001', '2012002' , '2012003','2012005', '2012006' , '2012007'])],
            'shr_credentials_code' => 'required',
            'identity_card_address' => 'required',
            'current_residence_address' => 'required',
            'sqjbr_name' => 'required',
            'sqjbr_phone' => 'required',
            'sqjbr_credentials_type' => ['required', Rule::in(['2012001', '2012002' , '2012003','2012005', '2012006' , '2012007'])],
            'sqjbr_credentials_code' => 'required',
            'shr_relationship_type' => ['required', Rule::in(['2015001', '2015002' , '2015003','2015004', '2015005'])],
            'relatives_phone' => 'required',
            'is_people' => ['required', Rule::in([0,1])],
            'ent_name' => 'required',
            'channel_type' =>  ['required', Rule::in(['2072001', '2072002' , '2072003','2072004', '2072005','2072006','2072007'])],
        ];
    }

    public function attributes(): array
    {
        return ['id' => '主键ID','apply_fee_type' => '申请费用类型','sg_time' => '事故时间','sg_prov' => '事故地点（省）','sg_city' => '事故地点（市）','sg_area' => '事故地点（区/县）','sg_address' => '事故详细地址','shr_name' => '受害人姓名','shr_phone' => '受害人联系方式','shr_credentials_type' => '受害人证件类型','shr_credentials_code' => '受害人证件号','identity_card_address' => '受害人身份证地址','current_residence_address' => '受害人现居住地址','sqjbr_name' => '申请经办人姓名','sqjbr_phone' => '申请经办人联系方式','sqjbr_credentials_type' => '申请经办人证件类型','sqjbr_credentials_code' => '申请经办人证件号','shr_relationship_type' => '与受害人关系','relatives_phone' => '亲属联系方式（默认空字符串）','is_people' => '是否个人（默认0）','ent_name' => '医疗/殡葬机构','channel_type' => '来源渠道（默认unknown）','created_at' => '创建时间','updated_at' => '更新时间',];
    }

}
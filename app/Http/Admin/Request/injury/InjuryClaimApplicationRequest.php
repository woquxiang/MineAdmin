<?php

namespace App\Http\Admin\Request\injury;

use Hyperf\Validation\Request\FormRequest;


class InjuryClaimApplicationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                                            
//            'claim_code' => 'required',
            'emergency_phone' => 'required',                            
            'application_date' => 'required',                            
//            'hospital_name' => 'required',
//            'total_compensation' => 'required',
//            'claim_status' => 'required',
//            'created_at' => 'required',
//            'updated_at' => 'required',
//            'created_by' => 'required',
//            'updated_by' => 'required',
//            'deleted_at' => 'required',
        ];
    }

    public function attributes(): array
    {
        return ['id' => '申请ID','claim_code' => '直赔编号','emergency_phone' => '报警电话','application_date' => '申请时间','hospital_name' => '医院名称','total_compensation' => '赔偿总金额','claim_status' => '赔偿状态','created_at' => '创建时间','updated_at' => '更新时间','created_by' => '创建者','updated_by' => '更新者','deleted_at' => '删除时间',];
    }

}
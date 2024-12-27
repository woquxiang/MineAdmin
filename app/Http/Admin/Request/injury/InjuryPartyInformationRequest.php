<?php

namespace App\Http\Admin\Request\injury;

use Hyperf\Validation\Request\FormRequest;


class InjuryPartyInformationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                                            
            'application_id' => 'required',                            
            'name' => 'required',                            
            'phone' => 'required',                            
            'gender' => 'required',                            
            'id_number' => 'required',                            
            'address' => 'required',                            
            'transportation_method' => 'required',                            
            'vehicle_owner' => 'required',                            
            'license_plate' => 'required',                            
            'vehicle_owner_address' => 'required',                            
            'insured_person' => 'required',                            
            'insurance_company' => 'required',                            
            'insurance_type' => 'required',                            
            'insurance_amount' => 'required',                            
            'created_at' => 'required',                            
            'updated_at' => 'required',                            
            'created_by' => 'required',                            
            'updated_by' => 'required',                            
            'deleted_at' => 'required',            
        ];
    }

    public function attributes(): array
    {
        return ['id' => '当事人ID','application_id' => '赔偿申请ID','name' => '姓名','phone' => '联系电话','gender' => '性别','id_number' => '身份证号码','address' => '住址','transportation_method' => '交通方式','vehicle_owner' => '车辆所有人','license_plate' => '车牌号','vehicle_owner_address' => '车辆所有人住址','insured_person' => '被保险人','insurance_company' => '保险公司','insurance_type' => '投保险别','insurance_amount' => '投保险金额','created_at' => '创建时间','updated_at' => '更新时间','created_by' => '创建者','updated_by' => '更新者','deleted_at' => '删除时间',];
    }

}
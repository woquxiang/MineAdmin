<?php

namespace App\Http\Admin\Request\healthcare;

use Hyperf\Validation\Request\FormRequest;


class PatientHospitalMappingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                                            
            'patient_id' => 'required',                            
            'hospital_id' => 'required',                            
            'hospital_patient_id' => 'required',                            
            'created_at' => 'required',                            
            'updated_at' => 'required',            
        ];
    }

    public function attributes(): array
    {
        return ['id' => '关联表ID','patient_id' => '本地患者ID','hospital_id' => '医院ID','hospital_patient_id' => '医院患者ID 用来查询医院数据','created_at' => '记录创建时间','updated_at' => '记录更新时间',];
    }

}
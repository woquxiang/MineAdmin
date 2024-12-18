<?php

namespace App\Http\Admin\Request\healthcare;

use Hyperf\Validation\Request\FormRequest;


class HospitalVisitsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                                            
            'visits_type' => 'required',                            
            'accident_id' => 'required',                            
            'patient_id' => 'required',                            
            'hospital_id' => 'required',                            
            'diagnosis' => 'required',                            
            'section' => 'required',                            
            'sickarea' => 'required',                            
            'bedno' => 'required',                            
            'doctor' => 'required',                            
            'i_time' => 'required',                            
            'o_time' => 'required',            
        ];
    }

    public function attributes(): array
    {
        return ['id' => '就诊记录ID','visits_type' => '就诊类型','accident_id' => '事故ID（可能为空）','patient_id' => '患者ID（可能为空）','hospital_id' => '医院ID','diagnosis' => '诊断信息','section' => '科室','sickarea' => '病区','bedno' => '床位号','doctor' => '主治医生','i_time' => '就诊时间','o_time' => '出院时间',];
    }

}
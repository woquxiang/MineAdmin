<?php

namespace App\Http\Admin\Request\healthcare;

use Hyperf\Validation\Request\FormRequest;


class TrafficIncidentsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                                            
            'accident_id' => 'required',                            
            'patient_id' => 'required',                            
            'accident_time' => 'required',                            
            'accident_location' => 'required',                            
            'description' => 'required',                            
            'cause' => 'required',            
        ];
    }

    public function attributes(): array
    {
        return ['id' => '主键','accident_id' => '事故ID','patient_id' => '患者ID（可能为空）','accident_time' => '事故发生时间','accident_location' => '事故发生地点','description' => '事故描述','cause' => '事故原因',];
    }

}
<?php

namespace App\Http\Admin\Request\healthcare;

use Hyperf\Validation\Request\FormRequest;


class PatientsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                                            
            'name' => 'required',                            
            'sex' => 'required',                            
            'id_card' => 'required',                            
            'birthday' => 'required',                            
            'phone' => 'required',            
        ];
    }

    public function attributes(): array
    {
        return ['id' => 'patients患者ID','name' => '患者姓名','sex' => '患者性别','id_card' => '患者身份证号','birthday' => '患者出生日期','phone' => '患者联系电话',];
    }

}
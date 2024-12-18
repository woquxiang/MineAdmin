<?php

namespace App\Http\Admin\Request\healthcare;

use Hyperf\Validation\Request\FormRequest;


class MedicalExpensesRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                                            
            'visit_id' => 'required',                            
            'expense_name' => 'required',                            
            'price' => 'required',                            
            'quantity' => 'required',            
        ];
    }

    public function attributes(): array
    {
        return ['id' => '费用ID','visit_id' => '就诊记录ID OR 案件ID','expense_name' => '费用名称（例如：住院费、检查费等）','price' => '费用金额','quantity' => '服务数量',];
    }

}
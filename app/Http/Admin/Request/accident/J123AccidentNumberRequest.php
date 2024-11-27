<?php

namespace App\Http\Admin\Request\accident;

use Hyperf\Validation\Request\FormRequest;


class J123AccidentNumberRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
//            'accident_number' => 'required|unique:j123_accident_number,accident_number,NULL,id,deleted_at,NULL',
            'accident_number' => 'required|unique:j123_accident_number,accident_number,' . $this->route('id') . ',id,deleted_at,NULL', // 验证必填且不重复且未删除
            'status' => 'required',
        ];
    }

    public function attributes(): array
    {
        return ['id' => '主键ID','accident_number' => '案件编号','status' => '状态 (0: 不需要, 1: 需要抓取)','created_at' => '创建时间','updated_at' => '更新时间','created_by' => '创建者','updated_by' => '更新者',];
    }
//
//    /**
//     * 自定义错误提示信息
//     */
//    public function messages(): array
//    {
//        return [
//            'accident_number.unique' => '案件编号已存在，请重新输入。',
//        ];
//    }
}
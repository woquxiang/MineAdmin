<?php

namespace App\Http\Admin\Request\feedback;

use Hyperf\Validation\Request\FormRequest;


class IssuesRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                                            
            'content' => 'required',                                                                                                                                                        
        ];
    }

    public function attributes(): array
    {
        return ['id' => '自增ID，唯一标识每条记录','content' => '投诉或建议的内容','contact_info' => '联系方式，用户提供的联系信息，例如电话或邮箱','created_at' => '创建时间，记录提交时间','updated_at' => '更新时间，记录最后更新时间','created_by' => '创建者，记录提交投诉或建议的用户ID','updated_by' => '更新者，记录最后处理该投诉或建议的用户ID',];
    }

}
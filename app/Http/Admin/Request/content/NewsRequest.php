<?php

namespace App\Http\Admin\Request\content;

use Hyperf\Validation\Request\FormRequest;


class NewsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                
            'title' => 'required',
            'short_description' => 'required',                            
            'content' => 'required',                                                                                                                                            
            'sort_order' => 'required',                            
            'author' => 'required',            
        ];
    }

    public function attributes(): array
    {
        return ['id' => '自增ID，唯一标识每条资讯','title' => '标题，不能为空','short_description' => '简短描述，资讯的简要内容，不能为空','content' => '详情，富文本内容，不能为空','created_at' => '创建时间，默认当前时间','updated_at' => '更新时间，自动更新为当前时间','created_by' => '创建者，记录创建者的ID，默认为0','updated_by' => '更新者，记录更新者的ID，默认为0','sort_order' => '排序，控制资讯的显示顺序，默认值为0','author' => '作者，不能为空',];
    }

}
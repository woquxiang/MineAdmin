<?php

namespace App\Http\Admin\Request\injury;

use Hyperf\Validation\Request\FormRequest;


class InjurySignatureRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected array $scenes = [
        'apiSignatureSave' => ['direct_compensation_id','signature_data'],
    ];

    public function rules(): array
    {
        return [                                            
            'application_id' => 'required',                            
            'direct_compensation_id' => 'required',                            
            'name' => 'required',                            
            'signature_data' => 'required',                            
            'created_at' => 'required',                            
            'updated_at' => 'required',                            
            'created_by' => 'required',                            
            'updated_by' => 'required',                            
            'deleted_at' => 'required',            
        ];
    }

    public function attributes(): array
    {
        return ['id' => '签名ID','application_id' => '赔偿申请ID','direct_compensation_id' => '直赔ID','name' => '姓名','signature_data' => '签名数据（Base64编码）','created_at' => '创建时间','updated_at' => '更新时间','created_by' => '创建者','updated_by' => '更新者','deleted_at' => '删除时间',];
    }

}
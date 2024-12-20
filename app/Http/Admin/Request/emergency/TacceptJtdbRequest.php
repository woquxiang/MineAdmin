<?php

namespace App\Http\Admin\Request\emergency;

use Hyperf\Validation\Request\FormRequest;


class TacceptJtdbRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [                                            
            'sjbm' => 'required',                            
            'hjdh' => 'required',
            'xcdz' => 'required',                            
            'zs' => 'required',                            
            'hzxm' => 'required',                            
            'xb' => 'required',                            
            'nl' => 'required',                            
            'mz' => 'required',                            
            'gj' => 'required',                            
            'lxr' => 'required',                            
            'lxdh' => 'required',                            
            'swdd' => 'required',                            
            'cphm' => 'required',                            
            'scdh' => 'required',                            
            'sjhm' => 'required',                            
            'yshm' => 'required',                            
            'hshm' => 'required',                            
            'gxsj' => 'required',            
        ];
    }

    public function attributes(): array
    {
        return ['id' => '自增ID，唯一标识每条记录','sjbm' => '急救事件唯一标识（关联急救事件数据事件编码字段）','hjdj' => '呼救电话','xcdz' => '事发地址','zs' => '患者病情描述','hzxm' => '患者姓名','xb' => '患者性别','nl' => '患者年龄','mz' => '患者民族','gj' => '患者国籍','lxr' => '联系人','lxdh' => '患者联系方式','swdd' => '送往地点','cphm' => '车牌号码','scdh' => '随车电话','sjhm' => '司机电话','yshm' => '医生电话','hshm' => '护士电话','gxsj' => '更新时间',];
    }

}
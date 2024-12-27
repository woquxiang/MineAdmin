<?php

namespace App\Model\injury;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class injury_claim_application
* @property string $id 
* @property string $claim_code 
* @property string $emergency_phone 
* @property string $application_date 
* @property string $hospital_name 
* @property string $total_compensation 
* @property string $claim_status 
* @property string $created_at 
* @property string $updated_at 
* @property string $created_by 
* @property string $updated_by 
* @property string $deleted_at 
*/
class InjuryClaimApplication extends MineModel
{
    protected ?string $table = 'injury_claim_application';

    protected array $fillable = ['id','claim_code','emergency_phone','application_date','hospital_name','total_compensation','claim_status','created_at','updated_at','created_by','updated_by','deleted_at',
    'case_code',
    'accident_time',
    'weather_condition',
    'accident_location','accident_description','handling_method'
];

    protected array $casts = ['id' => 'string','claim_code' => 'string','emergency_phone' => 'string','application_date' => 'string','hospital_name' => 'string','total_compensation' => 'string','claim_status' => 'string','created_at' => 'string','updated_at' => 'string','created_by' => 'string','updated_by' => 'string','deleted_at' => 'string',
    'case_code' => 'string',
    'accident_time' => 'string',
    'weather_condition' => 'string',
    'accident_location' => 'string',
    'accident_description' => 'string',
    'handling_method' => 'string',
];

    //关联查询当事人
    public function party_information()
    {
        return $this->hasMany(InjuryPartyInformation::class, 'application_id', 'id');
    }
}

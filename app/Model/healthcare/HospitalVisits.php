<?php

namespace App\Model\healthcare;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class hospital_visits
* @property string $id 
* @property string $visits_type 
* @property string $accident_id 
* @property string $patient_id 
* @property string $hospital_id 
* @property string $diagnosis 
* @property string $section 
* @property string $sickarea 
* @property string $bedno 
* @property string $doctor 
* @property string $i_time 
* @property string $o_time 
*/
class HospitalVisits extends MineModel
{
    public bool $timestamps = false;


    protected ?string $table = 'hospital_visits';

    protected array $fillable = ['id','visits_type','accident_id','patient_id','hospital_id','diagnosis','section','sickarea','bedno','doctor','i_time','o_time',];

    protected array $casts = ['id' => 'int','visits_type' => 'string','accident_id' => 'string','patient_id' => 'int','hospital_id' => 'string','diagnosis' => 'string','section' => 'string','sickarea' => 'string','bedno' => 'string','doctor' => 'string','i_time' => 'string','o_time' => 'string',];
}
<?php

namespace App\Model\healthcare;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class traffic_incidents
* @property string $id 
* @property string $accident_id 
* @property string $patient_id 
* @property string $accident_time 
* @property string $accident_location 
* @property string $description 
* @property string $cause 
*/
class TrafficIncidents extends MineModel
{
    public bool $timestamps = false;


    protected ?string $table = 'traffic_incidents';

    protected array $fillable = ['id','accident_id','patient_id','accident_time','accident_location','description','cause','allfee','prepay'];

    protected array $casts = ['id' => 'int','accident_id' => 'string','patient_id' => 'int','accident_time' => 'string','accident_location' => 'string','description' => 'string','cause' => 'string',
        'allfee'=>'string','prepay'=>'string'
        ];
}
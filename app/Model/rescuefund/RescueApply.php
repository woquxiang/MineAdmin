<?php

namespace App\Model\rescuefund;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class rescue_apply
* @property string $id 
* @property string $application_id 
* @property string $created_at 
* @property string $updated_at 
* @property string $created_by 
* @property string $updated_by 
* @property string $acceptPoint 
* @property string $accident_date 
* @property string $road 
* @property string $injured 
* @property string $type 
* @property string $reason 
* @property string $desc 
* @property string $relation_name 
* @property string $relation_phone 
* @property string $date 
*/
class RescueApply extends MineModel
{
    protected ?string $table = 'rescue_apply';

    protected array $fillable = ['id','application_id','created_at','updated_at','created_by','updated_by','acceptPoint','accident_date','road','injured','type','reason','desc','relation_name','relation_phone','date',];

    protected array $casts = ['id' => 'string','application_id' => 'string','created_at' => 'string','updated_at' => 'string','created_by' => 'string','updated_by' => 'string','acceptPoint' => 'string','accident_date' => 'string','road' => 'string','injured' => 'string','type' => 'string','reason' => 'string','desc' => 'string','relation_name' => 'string','relation_phone' => 'string','date' => 'string',];
}
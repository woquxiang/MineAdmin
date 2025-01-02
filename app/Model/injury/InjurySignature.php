<?php

namespace App\Model\injury;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class injury_signature
* @property string $id 
* @property string $application_id 
* @property string $direct_compensation_id 
* @property string $name 
* @property string $signature_data 
* @property string $created_at 
* @property string $updated_at 
* @property string $created_by 
* @property string $updated_by 
* @property string $deleted_at 
*/
class InjurySignature extends MineModel
{
    protected ?string $table = 'injury_signature';

    protected array $fillable = ['id','application_id','direct_compensation_id','name','signature_data','created_at','updated_at','created_by','updated_by','deleted_at',];

    protected array $casts = ['id' => 'string','application_id' => 'string','direct_compensation_id' => 'string','name' => 'string','signature_data' => 'string','created_at' => 'string','updated_at' => 'string','created_by' => 'string','updated_by' => 'string','deleted_at' => 'string',];
}
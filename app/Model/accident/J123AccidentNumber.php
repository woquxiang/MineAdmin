<?php

namespace App\Model\accident;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class j123_accident_number
* @property string $id 
* @property string $accident_number 
* @property string $status 
* @property string $created_at 
* @property string $updated_at 
* @property string $created_by 
* @property string $updated_by 
*/
class J123AccidentNumber extends MineModel
{

    use SoftDeletes;

    protected ?string $table = 'j123_accident_number';

    protected array $fillable = ['id','accident_number','status','created_at','updated_at','created_by','updated_by','deleted_at'];

    protected array $casts = ['id' => 'string','accident_number' => 'string','status' => 'integer','created_at' => 'string','updated_at' => 'string','created_by' => 'string','updated_by' => 'string',
            'deleted_at'=>'string'
        ];
}
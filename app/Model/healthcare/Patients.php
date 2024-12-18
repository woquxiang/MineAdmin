<?php

namespace App\Model\healthcare;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class patients
* @property string $id 
* @property string $name 
* @property string $sex 
* @property string $id_card 
* @property string $birthday 
* @property string $phone 
*/
class Patients extends MineModel
{
    public bool $timestamps = false;


    protected ?string $table = 'patients';

    protected array $fillable = ['id','name','sex','id_card','birthday','phone',];

    protected array $casts = ['id' => 'int','name' => 'string','sex' => 'string','id_card' => 'string','birthday' => 'string','phone' => 'string',];
}
<?php

namespace App\Model\abctest;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class test
* @property string $id 
* @property string $accident_number 
* @property string $event_date 
* @property string $weather 
* @property string $location 
* @property string $accident_scenario 
* @property string $accident_type 
* @property string $data_source 
* @property string $handling_method 
* @property string $management_department 
* @property string $accident_status 
*/
class Test extends MineModel
{
    protected ?string $table = 'test';

    protected array $fillable = ['id','accident_number','event_date','weather','location','accident_scenario','accident_type','data_source','handling_method','management_department','accident_status',];

    protected array $casts = ['id' => 'string','accident_number' => 'string','event_date' => 'string','weather' => 'string','location' => 'string','accident_scenario' => 'string','accident_type' => 'string','data_source' => 'string','handling_method' => 'string','management_department' => 'string','accident_status' => 'string',];
}
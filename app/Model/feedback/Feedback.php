<?php

namespace App\Model\feedback;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class feedback
* @property string $id 
* @property string $content 
* @property string $contact_info 
* @property string $created_at 
* @property string $updated_at 
* @property string $created_by 
* @property string $updated_by 
*/
class Feedback extends MineModel
{
    protected ?string $table = 'feedback';

    protected array $fillable = ['id','content','contact_info','created_at','updated_at','created_by','updated_by',];

    protected array $casts = ['id' => 'string','content' => 'string','contact_info' => 'string','created_at' => 'string','updated_at' => 'string','created_by' => 'string','updated_by' => 'string',];
}
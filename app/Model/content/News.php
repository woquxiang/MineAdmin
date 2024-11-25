<?php

namespace App\Model\content;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class news
* @property string $id 
* @property string $title 
* @property string $short_description 
* @property string $content 
* @property string $created_at 
* @property string $updated_at 
* @property string $created_by 
* @property string $updated_by 
* @property string $sort_order 
* @property string $author 
*/
class News extends MineModel
{
    protected ?string $table = 'news';

    protected array $fillable = ['id','title','short_description','content','created_at','updated_at','created_by','updated_by','sort_order','author',];

    protected array $casts = ['id' => 'string','title' => 'string','short_description' => 'string','content' => 'string','created_at' => 'string','updated_at' => 'string','created_by' => 'string','updated_by' => 'string','sort_order' => 'string','author' => 'string',];
}
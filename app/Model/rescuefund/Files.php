<?php

namespace App\Model\rescuefund;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
 * Class rescue_fund_files
 * @property string $id
 * @property string $application_id
 * @property string $file_id
 * @property string $file_name
 * @property string $file_path
 * @property string $file_type
 * @property string $file_type_id
 * @property string $file_size
 * @property string $uploaded_at
 */
class Files extends MineModel
{
    public bool $timestamps = false;

    protected ?string $table = 'rescue_fund_files';

    protected array $fillable = ['id','application_id','attachment_id','file_id','file_name','file_path','file_type','file_type_id','file_size','uploaded_at','is_returned'];

    protected array $casts = ['is_returned'=>'integer' , 'id' => 'string','application_id' => 'string','attachment_id'=>'integer' ,'file_id' => 'string','file_name' => 'string','file_path' => 'string','file_type' => 'string','file_type_id' => 'string','file_size' => 'string','uploaded_at' => 'string',];
}
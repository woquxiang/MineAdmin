<?php

namespace App\Model\emergency;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class taccept_jtdb
* @property string $id 
* @property string $sjbm 
* @property string $hjdj 
* @property string $xcdz 
* @property string $zs 
* @property string $hzxm 
* @property string $xb 
* @property string $nl 
* @property string $mz 
* @property string $gj 
* @property string $lxr 
* @property string $lxdh 
* @property string $swdd 
* @property string $cphm 
* @property string $scdh 
* @property string $sjhm 
* @property string $yshm 
* @property string $hshm 
* @property string $gxsj 
*/
class TacceptJtdb extends MineModel
{
    // public bool $timestamps = false;

    protected ?string $table = 'taccept_jtdb';

    protected array $fillable = ['id','sjbm','hjdh','xcdz','zs','hzxm','xb','nl','mz','gj','lxr','lxdh','swdd','cphm','scdh','sjhm','yshm','hshm','gxsj',];

    protected array $casts = ['id' => 'string','sjbm' => 'string','hjdh' => 'string','xcdz' => 'string','zs' => 'string','hzxm' => 'string','xb' => 'string','nl' => 'string','mz' => 'string','gj' => 'string','lxr' => 'string','lxdh' => 'string','swdd' => 'string','cphm' => 'string','scdh' => 'string','sjhm' => 'string','yshm' => 'string','hshm' => 'string','gxsj' => 'string',];
}
<?php

namespace App\Model\rescuefund;

use App\Model\Enums\User\Status;
use Hyperf\Collection\Arr;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Model\Model as MineModel;


/**
 * Class rescue_fund_regions
 * @property string $region_code
 * @property string $parent_id
 * @property string $city_code
 * @property string $province_code
 * @property string $region_name
 * @property string $city_name
 * @property string $province_name
 * @property string $region_level
 * @property string $full_name
 * @property string $created_at
 * @property string $updated_at
 */
class RescueFundRegions extends MineModel
{
    protected ?string $table = 'rescue_fund_regions';

    protected array $fillable = ['id','parent_id','city_code','province_code','region_name','city_name','province_name','region_level','full_name','created_at','updated_at','created_at','updated_at'];

    protected array $casts = ['id' => 'integer','parent_id' => 'integer','city_code' => 'string','province_code' => 'string','region_name' => 'string','city_name' => 'string','province_name' => 'string','region_level' => 'string','full_name' => 'string','created_at' => 'string','updated_at' => 'string',
    ];

    public function children()
    {
        // @phpstan-ignore-next-line
        return $this
            ->hasMany(self::class, 'parent_id', 'id')
//            ->where('status', Status::Normal)
//            ->orderBy('sort')
            ->select(['id','parent_id','region_name'])
            ->with('children');
    }
}
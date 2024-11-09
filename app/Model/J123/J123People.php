<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Model\J123;

use App\Model\Enums\User\Status;
use App\Model\Enums\User\Type;
use Carbon\Carbon;
use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Events\Creating;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\DbConnection\Model\Model;


final class J123People extends Model
{
    public bool $timestamps = false;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'j123_people';

    /**
     * 隐藏的字段列表.
     * @var string[]
     */
//    protected array $hidden = ['password', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'id',
        'accident_number',
        'name',
        'id_number',
        'vehicle_type',
        'phone',
        'car_type',
        'license_plate',
        'insurance_company',
        'responsibility'
    ];

    /**
     * The attributes that should be cast to native types.
     */
//    protected array $casts = [
//        'id' => 'integer',
//        'status' => Status::class,
//        'user_type' => Type::class,
//        'created_by' => 'integer',
//        'updated_by' => 'integer',
//        'created_at' => 'datetime',
//        'updated_at' => 'datetime',
//        'backend_setting' => 'json',
//    ];

}

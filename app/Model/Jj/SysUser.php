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

namespace App\Model\Jj;

use App\Model\Enums\User\Status;
use App\Model\Enums\User\Type;
use Carbon\Carbon;
use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Events\Creating;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\DbConnection\Model\Model;


final class SysUser extends Model
{
    protected ?string $connection = 'yc';
    public bool $timestamps = false;

    protected ?string $table = 'sys_user';

    protected array $fillable = ['user_id','dept_id','user_name','nick_name','user_type','email','phonenumber','sex','avatar','password','status','del_flag','login_ip','login_date','create_by','create_time','update_by','update_time','remark','role_ids','post_ids','extension_code','extension','insurance_code','insurance_report_url','latitude','longitude','manage_district',];

    protected array $casts = ['user_id' => 'int','dept_id' => 'int','user_name' => 'string','nick_name' => 'string','user_type' => 'string','email' => 'string','phonenumber' => 'string','sex' => 'string','avatar' => 'string','password' => 'string','status' => 'string','del_flag' => 'string','login_ip' => 'string','login_date' => 'string','create_by' => 'string','create_time' => 'string','update_by' => 'string','update_time' => 'string','remark' => 'string','role_ids' => 'string','post_ids' => 'string','extension_code' => 'string','extension' => 'string','insurance_code' => 'string','insurance_report_url' => 'string','latitude' => 'string','longitude' => 'string','manage_district' => 'string',];
}

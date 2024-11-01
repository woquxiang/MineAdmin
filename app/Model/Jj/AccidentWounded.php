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


//伤者信息表
final class AccidentWounded extends Model
{
    protected ?string $connection = 'yc';
    public bool $timestamps = false;

    protected ?string $table = 't_accident_wounded';
}

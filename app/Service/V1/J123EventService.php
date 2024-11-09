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

namespace App\Service\V1;

use App\Repository\J123\J123EventRepository;
use App\Service\IService;

final class J123EventService extends IService
{
    public function __construct(
        protected readonly J123EventRepository $repository
    ) {}
}

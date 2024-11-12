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

use App\Repository\J123\J123PeopleRepository;
use App\Service\IService;

final class J123PeopleService extends IService
{
    public function __construct(
        protected readonly J123PeopleRepository $repository
    ) {}

    /**
     * 根据事故编号查找当事人信息
     *
     * @param string $accidentNumber
     * @return array
     */
    public function findByAccidentNumber(string $accidentNumber): array
    {
        return $this->repository->findByAccidentNumber($accidentNumber);
    }

    /**
     * 检查当前用户是否与指定事故相关
     *
     * @param string $accidentNumber 事故编号
     * @param string $maskedIdCard   掩码格式的身份证号
     * @return bool
     */
    public function checkUserInvolvement(string $accidentNumber, string $maskedIdCard): bool
    {
        return $this->repository->existsByAccidentNumberAndIdCard($accidentNumber, $maskedIdCard);
    }
}

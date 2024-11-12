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

use App\Http\CurrentUser;
use App\Model\J123\J123Event;
use App\Repository\J123\J123EventRepository;
use App\Repository\J123\J123PeopleRepository;
use App\Service\IService;

final class J123EventService extends IService
{
    public function __construct(
        protected readonly J123EventRepository $repository,
        protected readonly J123PeopleRepository $peopleRepository,
        private readonly CurrentUser $currentUser

    ) {}


    /**
     * 根据事故编号查找事故信息
     *
     * @param string $accidentNumber
     * @return J123Event|null
     */
    public function findByAccidentNumber(string $accidentNumber): ?J123Event
    {
        return $this->repository->findOneByAccidentNumber($accidentNumber);
    }

    /**
     * 根据当前用户的身份证和姓名获取事故编号
     *
     * @param string $idNumber
     * @param string $name
     * @return array
     */
    public function getAccidentNumbersByIdAndName(string $idNumber, string $name): array
    {
        return $this->peopleRepository->findAccidentNumbersByIdAndName($idNumber, $name);
    }
}

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
use App\Model\Attachment;
use App\Model\J123\J123Event;
use App\Repository\AttachmentRepository;
use App\Repository\J123\J123EventRepository;
use App\Repository\J123\J123PeopleRepository;
use App\Service\IService;

final class J123EventService extends IService
{
    public function __construct(
        protected readonly J123EventRepository $repository,
        protected readonly J123PeopleRepository $peopleRepository,
        private readonly AttachmentRepository $attachmentRepository ,// Inject the AttachmentRepository
        private readonly CurrentUser $currentUser

    ) {}

    /**
     * 获取事故信息分页并关联人员信息
     *
     * @param array $filters
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function page(array $params, int $page = 1, int $pageSize = 10): array
    {
        // 1. 获取事故信息分页数据
        $events = $this->repository->page($params, $page, $pageSize);

        // 2. 根据事故编号获取人员信息
        foreach ($events['list'] as &$event) {
            $event['people'] = $this->peopleRepository->findByAccidentNumber($event['accident_number']);
        }

        return $events;
    }

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

    /**
     * 根据事故编号获取附件信息
     *
     * @param string $accidentNumber
     * @return array
     */
    public function getAttachmentsByAccidentNumber(string $accidentNumber): ?Attachment
    {
        return $this->attachmentRepository->findByAccidentNumber($accidentNumber);
    }
}

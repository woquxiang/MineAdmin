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

use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Http\CurrentUser;
use App\Model\J123\J123Event;
use App\Model\Permission\User;
use App\Repository\J123\J123EventRepository;
use App\Repository\J123\J123PeopleRepository;
use App\Repository\Permission\UserRepository;
use App\Service\IService;

final class MemberService extends IService
{
    public function __construct(
        protected readonly UserRepository $repository,
        protected readonly CurrentUser $currentUser
    ) {}

    /**
     * 根据当前用户ID更新身份证信息
     *
     * @param string $idCardName
     * @param string $idCardNumber
     * @return bool
     */
    public function updateIdCardInfo(string $idCardName, string $idCardNumber): ?User
    {
        // 获取当前用户ID
        $userId = $this->currentUser->id();

        // 查找当前用户
        $user = $this->repository->findById($userId);

        if (!$user) {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '获取手机号失败');
        }

        // 更新身份证信息
        $data = [
            'id_card_name'=>$idCardName,
            'id_card_number'=>$idCardNumber
        ];

        // 保存更新后的用户信息
        return $this->repository->saveById($userId,$data);
    }

}

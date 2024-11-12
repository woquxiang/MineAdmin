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

namespace App\Repository\J123;

use App\Model\Enums\User\Type;
use App\Model\J123\J123Event;
use App\Model\J123\J123People;
use App\Model\Permission\User;
use App\Repository\IRepository;
use Hyperf\Collection\Arr;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\ModelNotFoundException;

/**
 * Class UserRepository.
 * @extends IRepository<J123People>
 */
final class J123PeopleRepository extends IRepository
{
    public function __construct(protected readonly J123People $model) {}

//    public function findByUnameType(string $username, Type $userType = Type::SYSTEM): User
//    {
//        // @phpstan-ignore-next-line
//        return $this->model->newQuery()
//            ->where('username', $username)
//            ->where('user_type', $userType)
//            ->firstOrFail();
//    }

    /**
     * 根据事故编号查询当事人信息
     *
     * @param string $accidentNumber
     * @return array
     */
    public function findByAccidentNumber(string $accidentNumber): array
    {
        return $this->model->newQuery()->where('accident_number', $accidentNumber)->get()->toArray();
    }

    /**
     * 根据身份证和姓名获取所有相关的事故编号
     *
     * @param string $idNumber
     * @param string $name
     * @return array
     */
    public function findAccidentNumbersByIdAndName(string $idNumber, string $name): array
    {
        return $this->model->newQuery()
            ->where('id_number', $idNumber)
            ->where('name', $name)
            ->pluck('accident_number') // 返回所有相关的事故编号
            ->toArray();
    }

    /**
     * 根据事故编号和当事人姓名查找当事人记录
     *
     * @param string $accidentNumber 事故编号
     * @param string $name 当事人姓名
     * @return J123People|null 当事人记录或 null
     */
    public function findOneByAccidentNumberAndName(string $accidentNumber, string $name): ?J123People
    {
        return $this->model->newQuery()
            ->where('accident_number', $accidentNumber)
            ->where('name', $name)
            ->first();
    }

    /**
     * 检查是否存在匹配的事故编号和身份证号（掩码格式）
     *
     * @param string $accidentNumber 事故编号
     * @param string $maskedIdCard   掩码格式的身份证号
     * @return bool
     */
    public function existsByAccidentNumberAndIdCard(string $accidentNumber, string $maskedIdCard): bool
    {
        return J123People::query()
            ->where('accident_number', $accidentNumber)
            ->where('id_number', $maskedIdCard)
            ->exists();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(Arr::get($params, 'unique_username'), static function (Builder $query, $uniqueUsername) {
                $query->where('username', $uniqueUsername);
            })
            ->when(Arr::get($params, 'username'), static function (Builder $query, $username) {
                $query->where('username', 'like', '%' . $username . '%');
            })
            ->when(Arr::get($params, 'phone'), static function (Builder $query, $phone) {
                $query->where('phone', $phone);
            })
            ->when(Arr::get($params, 'email'), static function (Builder $query, $email) {
                $query->where('email', $email);
            })
            ->when(Arr::exists($params, 'status'), static function (Builder $query) use ($params) {
                $query->where('status', Arr::get($params, 'status'));
            })
            ->when(Arr::exists($params, 'user_type'), static function (Builder $query) use ($params) {
                $query->where('user_type', Arr::get($params, 'user_type'));
            })
            ->when(Arr::exists($params, 'nickname'), static function (Builder $query) use ($params) {
                $query->where('nickname', 'like', '%' . Arr::get($params, 'nickname') . '%');
            })
            ->when(Arr::exists($params, 'created_at'), static function (Builder $query) use ($params) {
                $query->whereBetween('created_at', [
                    Arr::get($params, 'created_at')[0] . ' 00:00:00',
                    Arr::get($params, 'created_at')[1] . ' 23:59:59',
                ]);
            })
            ->when(Arr::get($params, 'user_ids'), static function (Builder $query, $userIds) {
                $query->whereIn('id', $userIds);
            })
            ->when(Arr::get($params, 'role_id'), static function (Builder $query, $roleId) {
                $query->whereHas('roles', static function (Builder $query) use ($roleId) {
                    $query->where('role_id', $roleId);
                });
            });
    }

}

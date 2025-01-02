<?php

namespace App\Repository\Jj;

use App\Repository\IRepository;
use App\Model\Jj\SysUser as Model;
use Hyperf\Database\Model\Builder;


class SysUserRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    //根据 dept_id 获取所有用户
    public function getUsersByDeptId($deptId): array
    {
        return $this->getQuery()->where('dept_id', $deptId)->get()->toArray();
    }

    //根据 user_id 获取用户数据或者抛出异常 直接用内置的方法
    public function getUserByUserId($userId): Model
    {
        return $this->getQuery()->where('user_id', $userId)->firstOrFail();
    }

    //根据nick_name 获取用户数据
    public function getUserByNickName($nickName): ?Model
    {
        return $this->getQuery()->where('nick_name', $nickName)->first();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {

        if (isset($params['dept_id'])) {
            $query->where('dept_id', $params['dept_id']);
        }

        return $query;
    }
}

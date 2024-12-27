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

    public function handleSearch(Builder $query, array $params): Builder
    {

        if (isset($params['dept_id'])) {
            $query->where('dept_id', $params['dept_id']);
        }

        return $query;
    }
}

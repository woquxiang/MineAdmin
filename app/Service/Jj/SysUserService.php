<?php

namespace App\Service\Jj;

use App\Service\IService;
use App\Repository\Jj\SysUserRepository as Repository;



class SysUserService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}

    //根据 dept_id 获取所有用户
    public function getUsersByDeptId($deptId)
    {
        return $this->repository->getUsersByDeptId($deptId);
    }
}

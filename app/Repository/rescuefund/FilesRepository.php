<?php

namespace App\Repository\rescuefund;

use App\Repository\IRepository;
use App\Model\rescuefund\Files as Model;
use Hyperf\Collection\Arr;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;


class FilesRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    /**
     * 根据 application_id 返回所有文件，并按 file_type 分组
     *
     * @param int $applicationId
     * @return \Hyperf\Database\Model\Collection
     */
    public function findFilesByApplicationId(int $applicationId): array
    {
        // 查询所有文件，按 file_type 分组
        $files = $this->model->newQuery()
            ->where('application_id', $applicationId)
            ->orderBy('file_type_id','desc')
            ->get()->toArray();

        return $files;
    }

    /**
     * 根据 application_id 返回已回传的文件列表
     *
     * @param int $applicationId
     * @return array
     */
    public function findReturnedFilesByApplicationId(int $applicationId): array
    {
        // 查询已回传的文件
        $files = $this->model->newQuery()
            ->where('application_id', $applicationId)
            ->where('is_returned', 1)  // Filter by is_returned = 1
            ->orderBy('file_type_id','desc')
            ->get()->toArray();

        return $files;
    }
}

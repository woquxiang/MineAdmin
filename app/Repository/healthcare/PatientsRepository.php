<?php

namespace App\Repository\healthcare;

use App\Repository\IRepository;
use App\Model\healthcare\Patients as Model;
use Hyperf\Database\Model\Builder;


class PatientsRepository extends IRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    /**
     * 根据身份证查找患者
     */
    public function getPatientByIdCard(string $idCard): ?Model
    {
        return $this->model->newQuery()->where('id_card', $idCard)->first();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
                                                        
        if (isset($params['name'])) {
            $query->where('name', $params['name']);
        }
                                                                                                                        
        return $query;
    }
}

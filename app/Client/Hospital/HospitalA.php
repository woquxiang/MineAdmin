<?php

namespace App\Client\Hospital;

use App\Client\Hospital\CommonQueryService;

class HospitalA
{
    protected CommonQueryService $commonQueryService;

    public function __construct()
    {
//        $apiUrl = 'http://221.231.109.86:15000/api/query';
        $apiUrl = 'http://dev.ycjjwx.com/prod/v1/accident/test';
        $this->commonQueryService = new CommonQueryService($apiUrl);
    }

    public function queryFromHospital(string $sql): array|string
    {
        return $this->commonQueryService->queryFromHospital($sql);
    }
}

<?php

namespace App\Client\Hospital;

use App\Client\Hospital\CommonQueryService;

class Hospital120
{
    protected CommonQueryService $commonQueryService;

    public function __construct()
    {
        if (env('APP_ENV') === 'prod') {
            // 生产环境逻辑
//            $apiUrl = 'http://221.231.109.86:15000/api/query';
            $apiUrl = 'http://10.128.16.233:5000/api/query';
        } else {
            // 开发环境逻辑
            $apiUrl = 'http://dev.ycjjwx.com/prod/v1/accident/test';
        }

        $this->commonQueryService = new CommonQueryService($apiUrl);
    }

    //通过sql去查询数据
    public function queryFromHospital(string $sql): array|string
    {
        return $this->commonQueryService->queryFromHospital($sql);
    }


    //查询TACCEPT_JtDB 根据 SJBM字段倒叙 查询前500条数据
    public function queryTacceptJtdb(): array|string
    {
        $sql = "SELECT TOP 500 * FROM TACCEPT_JtDB ORDER BY SJBM DESC";
        return $this->queryFromHospital($sql);
    }
    

    //根据SJBM字段查询TACCEPT_JtDB表一条数据返回数组
    public function queryTacceptJtdbBySjbm(string $sjbm): array|string
    {
        $sql = "SELECT TOP 1  * FROM TACCEPT_JtDB WHERE SJBM = '{$sjbm}'";
        $result = $this->queryFromHospital($sql);

        if(!empty($result)){
            return $result[0];
        }

        return [];
    }

}

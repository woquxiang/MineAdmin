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

    /**
     * 根据 accid 查询 traffic_medical 表的一条数据 (SQL Server 格式)
     *
     * @param string $accid
     * @return array|string
     */
    public function queryMedicalRecordByAccid(string $accid): array|string
    {
        // 构造 SQL Server 查询语句
        $sql = "SELECT TOP 1 * FROM traffic_medical WHERE accid = '{$accid}'";
        $result = $this->queryFromHospital($sql);

        if(!empty($result)){
            return $result[0];
        }

        return [];
    }

    /**
     * 查询 traffic_register 表的记录，可以通过 accid 参数获取单条记录或所有记录
     *
     * @param string|null $accid
     * @return array|string
     */
    public function queryAllRegisterRecords(?string $accid = null): array|string
    {
        // 如果传入了 accid，则查询单条记录
        if ($accid) {
            $sql = "SELECT * FROM traffic_register WHERE accid = '{$accid}'";
        } else {
            // 否则查询所有记录
            $sql = "SELECT * FROM traffic_register ORDER BY accid DESC";
        }

        return $this->queryFromHospital($sql);
    }


    /**
     * 根据 accid 查询 traffic_detail 表的所有项目费用单
     *
     * @param string $accid
     * @return array|string
     */
    public function queryAllDetailRecordsByAccid(string $accid): array|string
    {
        // 构造 SQL Server 查询语句
        $sql = "SELECT * FROM traffic_detail WHERE accid = '{$accid}'";
        return $this->queryFromHospital($sql);
    }
}

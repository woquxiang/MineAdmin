<?php

namespace App\Client\Hospital;

use GuzzleHttp\Client;

class CommonQueryService
{
    protected string $apiUrl;

    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * 调用医院 API 执行 SQL 查询
     */
    public function queryFromHospital(string $sql): array|string
    {
        try {
            $client = new Client();

            if (env('APP_ENV') === 'prod') {
                // 生产环境逻辑
                $options = [
                    'json' => ['sql' => $sql]
                ];
            } else {
                // 开发环境逻辑
                $options = [
                    'json' => ['sql' => $sql ,'type'=>2],
                    //模拟的
                    'headers'=>[
                        'Authorization'=>'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzM3Mjk5MjksIm5iZiI6MTczMzcyOTkyOSwiZXhwIjoxNzMzNzMzNTI5LCJqdGkiOiIxIn0.U7Ttt-9JLHIlL3l0mm8LR32YkyyJE7Ry9mBAb1WhXUk'
                    ]
                ];
            }

            $response = $client->post($this->apiUrl, $options);

            $data = $response->getBody()->getContents();
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                if (env('APP_ENV') === 'prod') {
                    // 生产环境逻辑
                    return json_decode($data, true);
                } else {
                    // 开发环境逻辑
                    return json_decode($data, true)['data'];
                }
                /***
//                return json_decode($data, true);
                //默认测试的，因为直接对接的是线上的，不然本地环境不好测试
                return json_decode($data, true)['data'];
                 **/
            } else {
//                return ['error' => 'API 查询失败', 'details' => $data];
                return [];
            }
        } catch (\Exception $e) {
//            return ['error' => '调用 API 发生错误', 'message' => $e->getMessage()];
            return [];
        }
    }
}

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
            $response = $client->post($this->apiUrl, [
                'json' => ['sql' => $sql],
                //模拟的
                'headers'=>[
                    'Authorization'=>'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzM3Mjk5MjksIm5iZiI6MTczMzcyOTkyOSwiZXhwIjoxNzMzNzMzNTI5LCJqdGkiOiIxIn0.U7Ttt-9JLHIlL3l0mm8LR32YkyyJE7Ry9mBAb1WhXUk'
                ]
            ]);

            $data = $response->getBody()->getContents();
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                return json_decode($data, true);
            } else {
                return ['error' => 'API 查询失败', 'details' => $data];
            }
        } catch (\Exception $e) {
            return ['error' => '调用 API 发生错误', 'message' => $e->getMessage()];
        }
    }
}

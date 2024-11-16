<?php


namespace App\Client\RoadFund;

use GuzzleHttp\Client;
use Hyperf\Collection\Arr;
use Psr\Http\Message\ResponseInterface;

class Request
{
    protected $clientId;
    protected $clientSecret;
    protected $host;
    protected $token;
    protected $tokenExpiry;
    protected $headers = [];
    protected $data = [];
    protected $type = 'POST';
    protected $path = '';

    public function __construct(array $data = [])
    {
        $config = config('roadfund'); // 假设从 config/third_party.php 配置获取

        $this->clientId = Arr::get($config, 'client_id');
        $this->clientSecret = Arr::get($config, 'client_secret');
        $this->host = Arr::get($config, 'host');
        $this->data = $data;
    }

    // 获取 token（如果 token 无效，重新获取）
    public function getToken()
    {
        var_dump('时' , date('Y-m-d H:i:s',$this->tokenExpiry));
        if (!$this->token || $this->tokenExpiry < time()) {
            $this->requestToken();
        }

        return $this->token;
    }

//    public function getToken()
//    {
//        // 先从 Redis 获取 token
//        $cachedToken = $this->redis->get('third_party_token');
//
//        // 如果 token 存在且未过期，直接返回
//        if ($cachedToken && $this->redis->exists('third_party_token_expiry') && $this->redis->get('third_party_token_expiry') > time()) {
//            return $cachedToken;
//        }
//
//        // 如果 token 不存在或者已经过期，则重新获取
//        return $this->requestToken();
//    }

    // 发送请求获取 token
    protected function requestToken()
    {
        $url = $this->host . '/auth/oauth2/token';
        $authHeader =  'Basic ' . base64_encode( $this->clientId . ':' . $this->clientSecret);

        $client = new Client();

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => $authHeader,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => ['grant_type' => 'client_credentials']
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);

        if (isset($responseData['access_token'])) {
            $this->token = $responseData['access_token'];
            $this->tokenExpiry = time() + $responseData['expires_in'] - 60; // 提前 60 秒过期
        } else {
            throw new \Exception('获取 Token 失败: ' . $responseData['msg']);
        }
    }

    // 发送请求
    protected function sendRequest_bak(array $data)
    {
        $client = new Client();
        $url = $this->host . $this->path;

        $this->headers = [
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'application/json',
        ];

        $response = $client->request($this->type, $url, [
            'headers' => $this->headers,
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    // 发送请求
    protected function sendRequest(array $data)
    {
        $client = new Client();
        $url = $this->host . $this->path;

        // 设置请求头
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->getToken(),
        ];

//        $response = $roadFundApp->sendRequest([
//            'file' => '/path/to/your/file.txt', // 文件路径
//            'otherParam' => 'value',           // 其他参数
//        ]);


        // 检查是否有文件字段，如果有文件字段，则使用 multipart 格式
        if (isset($data['file']) && file_exists($data['file'])) {
            // 构造multipart数据
            $multipart = [
                [
                    'name'     => 'file',  // 这里的 'file' 是接口定义中的字段名
                    'contents' => fopen($data['file'], 'r'), // 'file' 是文件路径
                    'filename' => basename($data['file'])   // 文件名
                ]
            ];

            // 合并其他数据
            foreach ($data as $key => $value) {
                if ($key !== 'file') {  // 排除文件字段
                    $multipart[] = [
                        'name'     => $key,
                        'contents' => $value
                    ];
                }
            }

            // 使用 multipart 格式发送请求
            $response = $client->request($this->type, $url, [
                'headers'   => $this->headers,
                'multipart' => $multipart,  // 使用 multipart 来发送数据
            ]);
        } else {
            // 没有文件时，使用 JSON 格式发送请求
            $response = $client->request($this->type, $url, [
                'headers' => $this->headers,
                'json'    => $data,  // 使用 JSON 格式发送数据
            ]);
        }

        // 获取并返回响应内容
        return $response->getBody()->getContents();
    }

}

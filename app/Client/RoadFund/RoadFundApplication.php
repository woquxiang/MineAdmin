<?php

namespace App\Client\RoadFund;

use App\Client\RoadFund\Request;

class RoadFundApplication extends Request
{
    // 新增第三方申请信息
    public function createApplication(array $data)
    {
        $this->type = 'POST';
        $this->path = '/dljz/thirdSqxx/commitSqxx'; // 第三方接口路径
//        $this->data = array_merge($this->data, $data); // 合并请求数据
        $this->data = $data; // 合并请求数据

        return $this->exec(); // 执行请求
    }

    // 通过ID查询第三方申请信息
    public function getApplicationById(array $data)
    {
        $this->type = 'POST';
        $this->path = '/dljz/thirdSqxx/querySqxxById'; // 查询接口路径
//        $this->data = array_merge($this->data, $data); // 合并请求数据
        $this->data = $data; // 合并请求数据

        return $this->exec(); // 执行请求并返回结果
    }

    // 编辑第三方申请信息
    public function updateApplication(array $data)
    {
        $this->type = 'POST';
        $this->path = '/dljz/thirdSqxx/updateSqxx'; // 编辑接口路径
//        $this->data = array_merge($this->data, $data); // 合并请求数据
        $this->data = $data; // 合并请求数据

        return $this->exec(); // 执行请求并返回结果
    }

    // 文件上传接口
    public function uploadFile(array $data)
    {
        $this->type = 'POST';
        $this->path = '/third/fileCall/upload'; // 文件上传接口路径
        $this->data = $data; // 文件数据不需要合并，因为通常是上传文件

        return $this->exec(); // 执行请求并返回结果
    }

    // 获取文件预览地址
    public function getFileViewUrl(array $data)
    {
        $this->type = 'POST';
        $this->path = '/third/fileCall/getViewUrl/' . $data['fileId']; // 文件预览接口路径，动态替换fileId
        $this->data = $data; // 合并请求数据

        return $this->exec(); // 执行请求并返回结果
    }

    // 获取文件base64
    public function downloadFile(array $data)
    {
        $this->type = 'POST';
        $this->path = '/third/fileCall/downloadFile/' . $data['fileId']; // 文件预览接口路径，动态替换fileId
        $this->data = $data; // 合并请求数据

        return $this->exec(); // 执行请求并返回结果
    }

    // 执行请求
    protected function exec()
    {
        $response =  $this->sendRequest($this->data)->getContents();

        $this->data = [];
        return json_decode($response, true);
    }

    //删除 token
    public function deleteToken()
    {
        $this->token = null;
    }
}

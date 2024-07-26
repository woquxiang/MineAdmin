<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Http\Admin\Controller;

use App\Http\Admin\Request\UploadRequest;
use App\Http\Common\Controller\AbstractController;
use App\Service\DataCenter\AttachmentService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use League\Flysystem\FilesystemException;
use Mine\Annotation\Auth;
use Mine\Exception\MineException;
use Mine\MineController;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UploadController.
 */
#[Controller(prefix: 'system')]
class UploadController extends AbstractController
{
    #[Inject]
    protected AttachmentService $service;

    /**
     * 上传文件.
     */
    #[PostMapping('uploadFile'), Auth]
    public function uploadFile(UploadRequest $request): ResponseInterface
    {
        if ($request->validated() && $request->file('file')->isValid()) {
            $data = $this->service->upload(
                $request->file('file'),
                $request->all()
            );
            return empty($data) ? $this->error() : $this->success($data);
        }
        return $this->error(t('system.upload_file_verification_fail'));
    }

    /**
     * 上传图片.
     */
    #[PostMapping('uploadImage'), Auth]
    public function uploadImage(UploadRequest $request): ResponseInterface
    {
        if ($request->validated() && $request->file('image')->isValid()) {
            $data = $this->service->upload(
                $request->file('image'),
                $request->all()
            );
            return empty($data) ? $this->error() : $this->success($data);
        }
        return $this->error(t('system.upload_image_verification_fail'));
    }

    /**
     * 分块上传.
     */
    #[PostMapping('chunkUpload'), Auth]
    public function chunkUpload(UploadRequest $request): ResponseInterface
    {
        return ($data = $this->service->chunkUpload($request->validated())) ? $this->success($data) : $this->error();
    }

    /**
     * 保存网络图片.
     * @throws \Exception
     */
    #[PostMapping('saveNetworkImage'), Auth]
    public function saveNetworkImage(UploadRequest $request): ResponseInterface
    {
        return $this->success($this->service->saveNetworkImage($request->validated()));
    }

    /**
     * 获取当前目录所有文件和目录.
     */
    #[GetMapping('getAllFiles'), Auth]
    public function getAllFile(): ResponseInterface
    {
        return $this->success(
            $this->service->getAllFile($this->request->all())
        );
    }

    /**
     * 通过ID获取文件信息.
     */
    #[GetMapping('getFileInfoById')]
    public function getFileInfoByid(): ResponseInterface
    {
        return $this->success($this->service->read((int) $this->request->input('id', null)) ?? []);
    }

    /**
     * 通过HASH获取文件信息.
     */
    #[GetMapping('getFileInfoByHash')]
    public function getFileInfoByHash(): ResponseInterface
    {
        return $this->success($this->service->readByHash($this->request->input('hash', null)) ?? []);
    }

    /**
     * 根据id下载文件.
     */
    #[GetMapping('downloadById')]
    public function downloadById(): ResponseInterface
    {
        $id = $this->request->input('id');
        if (empty($id)) {
            return $this->error('附件ID必填');
        }
        $model = $this->service->read((int) $id);
        if (! $model) {
            throw new MineException('附件不存在', 500);
        }
        return $this->_download(BASE_PATH . '/public' . $model->url, $model->origin_name);
    }

    /**
     * 根据hash下载文件.
     */
    #[GetMapping('downloadByHash')]
    public function downloadByHash(): ResponseInterface
    {
        $hash = $this->request->input('hash');
        if (empty($hash)) {
            return $this->error('附件hash必填');
        }
        $model = $this->service->readByHash($hash);
        if (! $model) {
            throw new MineException('附件不存在', 500);
        }
        return $this->_download(BASE_PATH . '/public' . $model->url, $model->origin_name);
    }

    /**
     * 输出图片、文件.
     * @throws FilesystemException
     */
    #[GetMapping('showFile/{hash}')]
    public function showFile(string $hash): ResponseInterface
    {
        return $this->service->responseFile($hash);
    }
}

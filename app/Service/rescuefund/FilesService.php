<?php

namespace App\Service\rescuefund;

use App\Client\RoadFund\RoadFundApplication;
use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\Attachment;
use App\Repository\AttachmentRepository;
use App\Service\IService;
use App\Repository\rescuefund\FilesRepository as Repository;
use Hyperf\Collection\Collection;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Mine\Upload\UploadInterface;
use Symfony\Component\Finder\SplFileInfo;


class FilesService extends IService
{
    public function __construct(
        protected readonly Repository $filesRepository,
        protected readonly AttachmentRepository $repository,
        protected readonly UploadInterface $upload,
        protected readonly RoadFundApplication $roadFundApplication,

    ) {}

    public function upload(SplFileInfo $fileInfo, UploadedFile $uploadedFile, int $userId): Attachment
    {
        $fileHash = md5_file($fileInfo->getRealPath());
        if ($attachment = $this->repository->findByHash($fileHash)) {
            return $attachment;
        }
        $upload = $this->upload->upload(
            $fileInfo,
        );

        return $this->repository->create([
            'created_by' => $userId,
            'origin_name' => $uploadedFile->getClientFilename(),
            'storage_mode' => $upload->getStorageMode(),
            'object_name' => $upload->getObjectName(),
            'mime_type' => $upload->getMimeType(),
            'storage_path' => $upload->getStoragePath(),
            'hash' => $fileHash,
            'suffix' => $upload->getSuffix(),
            'size_byte' => $upload->getSizeByte(),
            'size_info' => $upload->getSizeInfo(),
            'url' => $upload->getUrl(),
        ]);
    }

    // 保存文件信息到 `rescue_fund_files` 表
    public function storeApplicationFiles(int $applicationId, array $fileData): void
    {
        // 遍历文件数据，保存到文件表
        foreach ($fileData as $file) {
            $attachment =  $this->repository->findById($file['attachment_id']);
            if($attachment){
                $this->filesRepository->create(
                    [
                        'application_id' => $applicationId,
                        'attachment_id' => $file['attachment_id'],  // 文件 ID
                        'file_id' => '',  // 文件 ID
                        'file_name' => $attachment->origin_name,  // 文件名称
                        'file_path' =>  '',  // 文件路径
                        'file_type' => $file['file_type'],  // 文件类型
                        'file_type_id' => $file['file_type_id'] ?? 0,  // 文件类型 ID，默认为 0
                        'file_size' => $attachment->size_info,  // 文件大小
                        'uploaded_at' => $attachment->created_at,  // 上传时间
                    ]
                );
            }
        }
    }

    public function findFilesByApplicationId(int $applicationId): array
    {
        $files = $this->filesRepository->findFilesByApplicationId($applicationId);

        foreach ($files as &$file){
            $attachment = $this->repository->findById($file['attachment_id']);
            $file['mime_type'] = $attachment['mime_type'];
            $file['local_url'] = $attachment['url'];
        }

        // 使用 array_reduce 简洁地按 file_type_id 分组
        $groupedFiles = array_reduce($files, function ($result, $file) {
            $result[$file['file_type_id']][] = $file;
            return $result;
        }, []);

        return $groupedFiles;
    }

    public function syncFile($file_id){
        $attachment_id = $this->filesRepository->findByField($file_id,'attachment_id');

        $info = $this->repository->findById($attachment_id);
        if(!$info){
            throw new BusinessException(code: ResultCode::UNPROCESSABLE_ENTITY);
        }

        $file_path = BASE_PATH . "/storage/uploads/{$info['storage_path']}/{$info['object_name']}";

        $result = $this->roadFundApplication->uploadFile(['file'=>$file_path]);

        if(0 !== $result['code']){
            throw new BusinessException(code: ResultCode::UNPROCESSABLE_ENTITY,message:  '文件上传上传失败');
        }

        $res_data = $result['data'];

        $this->filesRepository->updateById($file_id,[
            'file_id'=>$res_data['fileId'],
            'file_path'=>$res_data['fileUrl'],
            'uploaded_at'=>date("Y-m-d H:i:s"),
            'is_returned'=>1
        ]);
//        $result = $this->roadFundApplication->getFileViewUrl(['fileId'=>'850e7ca326a94e17a21f28318d3b4a22']);
    }

    public function getRepository(): AttachmentRepository
    {
        return $this->repository;
    }
}

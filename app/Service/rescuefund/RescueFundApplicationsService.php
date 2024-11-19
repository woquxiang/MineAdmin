<?php

namespace App\Service\rescuefund;

use App\Client\RoadFund\RoadFundApplication;
use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\rescuefund\RescueFundApplications;
use App\Repository\rescuefund\FilesRepository;
use App\Repository\rescuefund\RescueFundApplicationsRepository;
use App\Service\IService;
use App\Repository\rescuefund\RescueFundApplicationsRepository as Repository;



class RescueFundApplicationsService extends IService
{
    public function __construct(
        protected readonly Repository $repository,
        protected readonly RoadFundApplication $roadFundApplication,
        protected readonly FilesRepository $filesRepository,
        protected readonly RescueFundApplicationsRepository $rescueFundApplicationsRepository,

    ) {}

    public function getUserDetail(int $id, int $createdBy): ?RescueFundApplications
    {
        return $this->repository->getDetailByIdAndCreatedBy($id, $createdBy);
    }

    public function apply(int $id){
        $file_info = $this->filesRepository->findReturnedFilesByApplicationId($id);
        $fileDto = [];
        foreach ($file_info as $file){
            $_file = [
                'fileId'=>$file['file_id'],
                'fileName'=>$file['file_name'],
                'filePath'=>$file['file_path'],
                'fileTypeId'=>$file['file_type_id'],
                'fileSize'=>$file['file_size'],
            ];
            $fileDto[] = $_file;
        }

        $appInfo = $this->rescueFundApplicationsRepository->findById($id);
        if (!$appInfo){
            throw new BusinessException(code: ResultCode::UNPROCESSABLE_ENTITY);
        }

        $data = [
            'fileDto'=>$fileDto,
            'applyFeeType'=>$appInfo['apply_fee_type'],
            'sgTime'=>$appInfo['sg_time'],
            'sgProv'=>$appInfo['sg_prov'],
            'sgCity'=>$appInfo['sg_city'],
            'sgArea'=>$appInfo['sg_area'],
            'sgAddress'=>$appInfo['sg_address'],
            'shrName'=>$appInfo['shr_name'],
            'shrPhone'=>$appInfo['shr_phone'],
            'shrCredentialsType'=>$appInfo['shr_credentials_type'],
            'shrCredentialsCode'=>$appInfo['shr_credentials_code'],
            'identityCardAddress'=>$appInfo['identity_card_address'],
            'currentResidenceAddress'=>$appInfo['current_residence_address'],
            'sqjbrName'=>$appInfo['sqjbr_name'],
            'sqjbrPhone'=>$appInfo['sqjbr_phone'],
            'sqjbrCredentialsType'=>$appInfo['sqjbr_credentials_type'],
            'sqjbrCredentialsCode'=>$appInfo['sqjbr_credentials_code'],
            'shrRelationshipType'=>$appInfo['shr_relationship_type'],
            'relativesPhone'=>$appInfo['relatives_phone'],
            'isPeople'=>$appInfo['is_people'],
            'entName'=>$appInfo['ent_name'],
            'channelType'=>$appInfo['channel_type'],
        ];

        $result = $this->roadFundApplication->createApplication($data);

        //$this->rescueFundApplicationsRepository->updateById($id,['sqxx_id'=>66666,'ent_name'=>666666666]);

        var_dump($data);
        var_dump($result);
        var_dump(json_encode($result));

        if(0 !== $result['code']){
            throw new BusinessException(code: ResultCode::UNPROCESSABLE_ENTITY,message: $result['msg'] );
        }else{
            $appInfo->sqxx_id = $result['data'];
            $appInfo->save();
        }
    }
}

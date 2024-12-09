<?php

namespace App\Service\rescuefund;

use App\Client\RoadFund\RoadFundApplication;
use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Repository\rescuefund\FilesRepository;
use App\Repository\rescuefund\RescueFundApplicationsRepository;
use App\Service\IService;
use App\Repository\rescuefund\RescueFundStatusRepository as Repository;
use App\Model\rescuefund\RescueFundStatus; // 引入模型类

class RescueFundStatusService extends IService
{
    public function __construct(
        protected readonly Repository $repository,
        protected readonly RoadFundApplication $roadFundApplication,
        protected readonly FilesRepository $filesRepository,
        protected readonly RescueFundApplicationsRepository $rescueFundApplicationsRepository,
    ) {}

    /**
     * 根据 application_id 获取垫付状态信息并同步
     *
     * @param int $application_id 主表的 application_id
     * @return bool 是否同步成功
     */
    public function syncDataByApplicationId(int $application_id): bool
    {
        try {
            // 第一步：从 rescue_fund_applications 表中获取 sqxx_id
            $application = $this->rescueFundApplicationsRepository->findById($application_id);

            if (!$application) {
                throw new BusinessException(code: ResultCode::UNPROCESSABLE_ENTITY, message: '未找到指定的 application_id 对应的记录');
            }

            $sqxx_id = $application->sqxx_id; // 获取 sqxx_id

            // 第二步：通过 sqxx_id 调用第三方 API 查询垫付状态信息
            $data = ['id' => $sqxx_id]; // 创建请求数据
            $response = $this->roadFundApplication->getApplicationById($data); // 调用 API 获取垫付状态信息

            if (!$response || $response['success'] !== true) {
                throw new BusinessException(code: ResultCode::FAIL, message: '调用第三方 API 查询失败');
            }

            if(0 !== $response['code']){
                throw new BusinessException(code: ResultCode::UNPROCESSABLE_ENTITY,message: $response['msg'] );
            }else{
                // 第三步：同步数据到 fund_advance_status 表
                unset( $response['data']['id']);
                $this->syncDataToFundAdvanceStatus(array_merge($response['data'] , ['application_id'=>$application_id,'sqxx_id'=>$sqxx_id])); // 将返回的数据同步到表中
            }
            return true;

        } catch (\Throwable $e) {
            // 记录日志或处理异常
            // Log::error('同步数据失败：' . $e->getMessage());
            throw new BusinessException(code: ResultCode::FAIL, message: '同步失败: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 将数据同步到 fund_advance_status 表
     *
     * @param array $data 要同步的数据
     * @return void
     */
    private function syncDataToFundAdvanceStatus(array $data): void
    {
        try {
            // 检查返回的数据是否完整
            $this->validateSyncData($data); // 数据验证

            // 使用 sqxx_id 查找是否已存在记录
            $existingRecord = $this->repository->findOneBySqxxId($data['sqxx_id']);

            if ($existingRecord) {
                // 如果记录存在，进行更新
                $this->updateFundAdvanceStatus($existingRecord, $data);
            } else {
                // 如果记录不存在，进行创建
                $this->createFundAdvanceStatus($data);
            }
        } catch (\Exception $e) {
            // 处理异常
            throw new BusinessException(code: ResultCode::FAIL, message: '同步数据到 fund_advance_status 失败：' . $e->getMessage());
        }
    }

    /**
     * 验证同步数据的完整性
     *
     * @param array $data
     * @return void
     * @throws BusinessException
     */
    private function validateSyncData(array $data): void
    {
        if (empty($data['sqxx_id']) || empty($data['applyFeeType']) || empty($data['wechatSqxxState'])) {
            throw new BusinessException(code: ResultCode::NOT_ACCEPTABLE, message: '缺少必要的字段，如 sqxx_id, applyFeeType 或 adjustmentMoney');
        }
    }

    /**
     * 更新 fund_advance_status 表中的记录
     *
     * @param RescueFundStatus $existingRecord
     * @param array $data
     * @return void
     */
    private function updateFundAdvanceStatus(RescueFundStatus $existingRecord, array $data): void
    {
        $existingRecord->update([
            'wechat_sqxx_state' => $data['wechatSqxxState'] ?? null,
            'approve_user_name' => $data['approveUserName'] ?? null,
            'give_money_time' => $data['giveMoneyTime'] ?? null,
            'apply_fee_type' => $data['applyFeeType'] ?? null,
            'adjustment_money' => $data['adjustmentMoney'] ?? 0,
            'wechat_approve_state' => $data['wechatApproveState'] ?? null,
            'return_reason' => $data['returnReason'] ?? null,
//            'file_list' => json_encode($data['fileList'] ?? []), // 将文件列表转为 JSON 字符串
            'file_list' =>$data['fileList'] ?? [], // 将文件列表转为 JSON 字符串
        ]);
    }

    /**
     * 创建新的 fund_advance_status 记录
     *
     * @param array $data
     * @return void
     */
    private function createFundAdvanceStatus(array $data): void
    {
        $this->repository->create([
            'application_id' => $data['application_id'],
            'sqxx_id' => $data['sqxx_id'],
            'wechat_sqxx_state' => $data['wechatSqxxState'] ?? null,
            'approve_user_name' => $data['approveUserName'] ?? null,
            'give_money_time' => $data['giveMoneyTime'] ?? null,
            'apply_fee_type' => $data['applyFeeType'] ?? null,
            'adjustment_money' => $data['adjustmentMoney'] ?? 0,
            'wechat_approve_state' => $data['wechatApproveState'] ?? null,
            'return_reason' => $data['returnReason'] ?? null,
//            'file_list' => json_encode($data['fileList'] ?? []), // 将文件列表转为 JSON 字符串
            'file_list' =>$data['fileList'] ?? [], // 将文件列表转为 JSON 字符串
        ]);
    }

}

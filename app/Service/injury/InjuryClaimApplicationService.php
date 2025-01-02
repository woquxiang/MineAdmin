<?php

namespace App\Service\injury;

use App\Service\IService;
use App\Repository\injury\InjuryClaimApplicationRepository as Repository;
use App\Repository\injury\InjuryPartyInformationRepository;
use Hyperf\DbConnection\Db;
use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use Hyperf\Redis\Redis;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use App\Repository\AttachmentRepository;
//导入二维码
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use App\Repository\Jj\SysUserRepository;
class InjuryClaimApplicationService extends IService
{
    public function __construct(
        protected readonly Repository $repository,
        //导入当事人仓库层
        protected readonly InjuryPartyInformationRepository $partyRepository,
        protected readonly Redis $redis,
        protected readonly AttachmentRepository $attachmentRepository,
        protected readonly SysUserRepository $sysUserRepository,
    ) {}

    
    
    public function create(array $data):mixed
    {
        //创建主表数据
        //直赔编号随机数

        //增加事务
        Db::beginTransaction();
        try{
            $data['claim_code'] = date('ymdHi'). mt_rand(100,999) . mt_rand(100,999);

            $application = $this->repository->create($data);
    
            //循环创建当事人数据
            foreach($data['party_information'] as $party){
                $party['application_id'] = $application->id;
                $party['direct_compensation_id'] =  date('ymdHis'). mt_rand(100,999) . mt_rand(100,999) . mt_rand(100,999);

                $this->partyRepository->create($party);
            }

        } catch(\Throwable $ex){
            Db::rollBack();
            print_r($ex->getMessage());
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '创建失败');
        }

        //需要生成直赔pdf任务，通过redis队列
        $this->redis->rpush('generateDirectCompensationPdf', $application->id);

        //提交事务      
        Db::commit();

        return $application;
    }

    //详情
    public function details(int $id):mixed
    {
        $result =  $this->repository->getInjuryClaimApplicationByIdWithParty($id);
        //循环当事人信息获取附件
        foreach($result->party_information as $party){
            $object_name = $party->direct_compensation_id.'_notice.pdf';
            $attachment = $this->attachmentRepository->getAttachmentByObjectName($object_name);
            $party->attachment = $attachment;
        }

        return $result;
    }

    //更新
    public function updateById(mixed $id, array $data):mixed
    {
        //增加事务
        Db::beginTransaction();
        try {
            // 更新主表数据
            unset($data['claim_code']);
            $this->repository->updateById($id, $data);
    
            // 获取目前所有当事人
            $existingParties = $this->partyRepository->getPartyByApplicationId($id);
            $existingPartyIds = array_column($existingParties, 'id');
            
            // 记录新数据中的ID，用于后续判断需要删除的记录
            $updatedPartyIds = [];
            
            // 遍历新的当事人数据
            foreach($data['party_information'] as $party) {
                if (isset($party['id'])) {
                    //当事人数据
                    $partyInfo = $this->partyRepository->findById($party['id']);

                    // 有ID，更新已存在的记录
                    unset($party['direct_compensation_id']);
                    $this->partyRepository->updateById($party['id'], $party);
                    //删除附件
                    $this->attachmentRepository->deleteByObjectName($partyInfo['direct_compensation_id'].'_notice.pdf');
                    $updatedPartyIds[] = $party['id'];
                } else {
                    // 没有ID，创建新记录
                    $party['application_id'] = $id;
                    $party['direct_compensation_id'] =  date('ymdHis'). mt_rand(100,999) . mt_rand(100,999) . mt_rand(100,999);
                    $newParty = $this->partyRepository->create($party);
                    $updatedPartyIds[] = $newParty->id;
                }
            }
            
            // 找出需要删除的记录（在原数据中存在但新数据中不存在的记录）
            $partyIdsToDelete = array_diff($existingPartyIds, $updatedPartyIds);
            if (!empty($partyIdsToDelete)) {
                // 循环删除
                foreach($partyIdsToDelete as $did){
                    $this->partyRepository->deleteById($did);
                }
            }
    
        } catch(\Throwable $ex) {
            Db::rollBack();
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '更新失败');
        }

        //需要生成直赔pdf任务，通过redis队列
        $this->redis->rpush('generateDirectCompensationPdf', $id);
    
        //提交事务      
        Db::commit();
    
        return $this->repository->getInjuryClaimApplicationByIdWithParty($id);
    }

    //通过 dompdf 生成直赔pdf
    public function generateDirectCompensationPdf(int $id):void
    {
        //获取当事人数据
        $party = $this->partyRepository->getPartyByApplicationId($id);

        //循环生成pdf
        foreach($party as $person){
            //不是伤者就跳过
            if($person['is_injured'] != 1){
                continue;
            }

            //生成pdf
            $options = new Options();

            $options->set('defaultFont', 'SimSun'); // 设置默认字体（可选）
            $dompdf = new Dompdf($options);
    
            $html = '';
    
            $aa = "&nbsp;&nbsp;&nbsp;&nbsp;";
    
            
            //根据身份证号提取出年龄
            $idCard =  $person['id_number'];
            $age = (int) date('Y') - (int) substr($idCard, 6, 4);

            //二维码数据 {"directIndemnityId":"Y+RSvm+T8b1yMLp9RghoLAJe9MpJU9YAUhBUlc5VCns=","injuredCardId":"320926196803118227","injuredAge":"56","hospital":"167","injuredName":"丁祥兰"}
    
            $qrData = [
                'directIndemnityId' => $this->encryptData($person['direct_compensation_id']),
                'injuredCardId' => $person['id_number'],
                'injuredAge' => $age,
                'hospital' => $person['hospital_id'],
                'injuredName' => $person['name'],
            ];
            $qrData = json_encode($qrData,JSON_UNESCAPED_UNICODE);

            //用QrCode生成二维码
            $qrCode = QrCode::create($qrData)
            ->setSize(150)
            ->setMargin(3)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
        
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            
            // 获取二维码的Base64编码
            $dataUri = $result->getDataUri();

            //预计到院时间：当天
            $expectedArrivalTime = date('Y-m-d');

            //医院名称
            $hospital = $this->sysUserRepository->getUserByUserId($person['hospital_id']);
            if(!$hospital){
                throw new Exception('医院不存在');
            }

            
            $html .= <<<HTML
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>道路交通事故人伤绿色通道医疗救助救治急诊直赔通知书</title>
            <style>
                body,{
                    font-family: SimSun;
                    box-sizing: border-box;
                }
                h1,h2,h3,h4,h5,h6,p {
                    padding: 0;
                    margin: 0;
                    line-height: 1;
                }
                .title {
                    text-align: center;
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                .doc-number {
                    text-align: center;
                }
               .word-break {
                    word-break: break-word;
                    line-height: 1.5;
               }
    
                .indent-2 {
                    /*padding-left: 2em;*/
                    /*text-indent: 2em;*/
                }
    
                .main-content {
                    margin-bottom: 15px;
                }
                .notice {
                    text-indent: 2em;
                    margin-bottom: 15px;
                }
                .person-info {
                    margin-bottom: 15px;
                }
                .info-row {
                    margin-bottom: 15px;
                }
                .info-item {
                    display: inline-block;
                    width: 45%;
                }
                .footer-content {
                    margin-top: 15px;
                }
                .stamp {
                    position: absolute;
                    width: 150px;
                }
                .font-bold {
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="title font-bold">
                <h2 style="font-weight: 900;">道路交通事故人伤绿色通道</h2>
                <h2>医疗救助救治急诊直赔通知书</h2>
            </div>
    
            <div class="doc-number">
                直赔服急(通){$person['direct_compensation_id']}号
            </div>
    
            <div class="hospital indent-2 word-break" style="padding-bottom: 5px;">
                {$aa}{$hospital['nick_name']}：
            </div>
    
            <div class="main-content indent-2 word-break" style="line-height: 2;">
                {$aa}根据申请材料,经我中心审核确认,该起人伤交通事故符合《中华人民共和国保险法》、《机动车交通事故责任强制保险条例》、《机动车交通事故责在强制保险条款》、《机动车第三者责任保险条款》、《盐城市道路交通事故人伤绿色通道定点救治医院直赔服务实施方案>的相关规定。请贵院接本通知后对伤者张福军提供人伤绿色通道急诊直赔医疗救助救治服务。(注，急诊直赔服务只直赔24小时内产生的首次急诊医疗费)        
                    </div>
    
    
            <div class="person-info">
                <div class="info-row">
                    <div class="info-item">伤者姓名：{$person['name']}</div>
                    <div class="info-item">性别：{$person['gender']}</div>
                </div>
                <div class="info-row">
                    <div class="info-item">年龄：{$age}</div>
                    <div class="info-item">身份证号：{$idCard}</div>
                </div>
            </div>
            
            <div style="position: relative;height: 130px;margin-top: 30px;">
                <div style="position: absolute;left: 0;top: 0;width: 100px;vertical-align: middle;">
                    <img src="{$dataUri}" style="width: 100px;height: 100px;" alt="QR Code">
                </div>
                <div style="position: absolute;right: 0;top: 0;text-align: right;vertical-align: middle;">
                    <div>预计到院时间：{$expectedArrivalTime}</div>
                    <div style="margin-top: 50px; position: relative;text-align: center;">
                        <div style="text-align: right;">{$expectedArrivalTime}</div>
    <!--                    <img src="{$dataUri}" style="position: absolute; top: -38px;left: 80px; width: 100px; height: 100px;" alt="QR Code">-->
                    </div>
                </div>
            </div>
    
            <div style="position: relative;height: 100px;margin-top: 10px;">
                <div style="position: absolute;left: 0;top: 0;width: 100px;vertical-align: middle;">
                    <div>{$person['name']}</div>
                </div>
                <div style="position: absolute;right: 0;top: 0;text-align: right;vertical-align: middle;">
                    <div>联系电话：{$person['phone']}</div>
                </div>
            </div>
    
        </body>
    </html>
HTML;
    
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
    
            //保存pdf
            $output = $dompdf->output();
            $filename = $person['direct_compensation_id'].'_notice.pdf';

            //保存到指定目录 放到 stroage/uploads/{日期}/direct_compensation/
            $path = BASE_PATH.'/storage/export/'.date('Y-m-d').'/direct_compensation/';


            if(!is_dir($path)){
                mkdir($path, 0777, true);
            }
            //判断文件是否存在
            if(file_exists($path.$filename)){
                unlink($path.$filename);
                //根据object_name 删除附件表数据
                $this->attachmentRepository->deleteByObjectName($filename);
            }

            //如果保存不成功，则抛出异常
            if(!file_put_contents($path.$filename, $output)){
                throw new Exception('保存失败');
            }

            //判读是开发环境还是生产环境
            $env = env('APP_ENV');
            $url2 = '/export/'.date('Y-m-d').'/direct_compensation/' . $filename;
            if($env == 'prod'){
                $url = 'https://dev.ycjjwx.com/prod' . $url2;
            }else{
                $url = env('APP_URL') . $url2;
                //$url = 'http://localhost:9501' . $url2;
            }

            //写入附件表
            $res = $this->attachmentRepository->create([
                'created_by' => 1,
                'origin_name' => $filename,
                'storage_mode' => 'local',
                'object_name' => $filename,
                'mime_type' => 'application/pdf',
                'storage_path' => date('Y-m-d'),
                'hash' => md5($path.$filename),
                'suffix' => 'pdf',
                'size_byte' => filesize($path.$filename),
                'size_info' => filesize($path.$filename),
                'url' => $url,
            ]);

            if(!$res){
                throw new Exception('sql保存失败');
            }

            //$dompdf->stream('direct_compensation.pdf', ['Attachment' => 0]);
        }
    }

    //用 aes/ecb/pkcs5padding token为 b6d767d2f8ed5d21a44b0e5886680cb9
    public function encryptData(string $data): string
    {
        $key = 'b6d767d2f8ed5d21a44b0e5886680cb9'; // 密钥
        
        // 使用 PKCS5Padding
       
        // 使用 AES-ECB 模式加密
        $encrypted = openssl_encrypt(
            $data,
            'AES-256-ECB', // AES-ECB 模式
            $key,
            OPENSSL_RAW_DATA // 输出原始数据
        );
        
        // 返回 base64 编码的加密结果
        return base64_encode($encrypted);
    }

    //解密
    public function decryptData(string $data): string
    {
        $key = 'b6d767d2f8ed5d21a44b0e5886680cb9'; // 密钥
        return openssl_decrypt(base64_decode($data), 'AES-256-ECB', $key, OPENSSL_RAW_DATA);
    }
}

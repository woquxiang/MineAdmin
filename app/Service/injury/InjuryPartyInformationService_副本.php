<?php

namespace App\Service\injury;

use App\Service\IService;
use App\Repository\injury\InjuryPartyInformationRepository as Repository;
use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Repository\injury\InjuryClaimApplicationRepository;
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
use App\Repository\injury\InjurySignatureRepository;


class InjuryPartyInformationService extends IService
{
    public function __construct(
        protected readonly Repository $repository,
        protected readonly InjuryClaimApplicationRepository $injuryClaimApplicationRepository,
        protected readonly InjurySignatureRepository $injurySignatureRepository,
        protected readonly AttachmentRepository $attachmentRepository
    ) {}

    //通过直赔ID 查询直赔信息
    public function findByDirectCompensationId($directIndemnityId)
    {
        $result = $this->repository->findByDirectCompensationId($directIndemnityId);
        if(!$result){
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '没有找到记录');
        }else{
            return $this->handleData($result);
        }
    }

    //医院当天所有伤者列表
    public function getTodayInjuredList($hospitalId, $time)
    {
        $list =  $this->repository->getTodayInjuredList($hospitalId, $time);
        $data = [];
        foreach($list as $item){
            $data[] = $this->handleData($item);
        }
        return $data;
    }

    //写一个处理数据的方法 只要支持单条数据
    public function handleData($data)
    {
        //判断是集合还是单条数据
        if(is_array($data)){
            $result = $data;
        }else{
            $result = $data->toArray();
        }

        //根据身份证号提取出年龄
        $idCard =  $result['id_number'];
        $age = (int) date('Y') - (int) substr($idCard, 6, 4);

        $data = [
            'injuredId' => $result['direct_compensation_id'],
            'injuredName' => $result['name'],
            'injuredGender' => $result['gender'],
            'injuredAge' => $age,
            'injuredCardId' => $result['id_number'],
            'injuredPhone' => $result['phone'],
            'injuredAddress' => $result['address'],
            //交通方式
            'injuredTrafficMethod' => $result['transportation_method'],
            'arrivedTime' => date("Y-m-d"),
            //事故相关信息
            'accidentAddress' => $result['application']['accident_location'],
            'accidentTime' => $result['application']['accident_time'],
        ];
        return $data;
    }

    //签名合成
    public function mergeSignature($directCompensationId)
    {
        //签名列表
        $signatureList = $this->injurySignatureRepository->findByDirectCompensationId($directCompensationId);
        
        if(empty($signatureList)){
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '签名不存在');
        }

        //生成pdf
        $options = new Options();

        $options->set('defaultFont', 'SimSun'); // 设置默认字体（可选）
        $dompdf = new Dompdf($options);

        $html = '';

        $emptySpace = "&nbsp;&nbsp;&nbsp;&nbsp;";

    
        $writer = new PngWriter();
        //$result = $writer->write($qrCode);
        
        // 获取二维码的Base64编码
        //$dataUri = $result->getDataUri();


    
        $html .= <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>直赔须知</title>
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
                .header {
                    text-align: center;
                    font-size: 18px;
                    font-weight: bold;
                    line-height: 1.5;
                    padding: 10px 0;
                }
                .p1 p {
                    word-break: break-word;
                    padding:5px 0;
                    line-height: 1.2;
                }
                .stamp {
                    position: relative;
                    left: 100px;
                    color: red;
                    font-size: 12px;
                }
                .table-border {
                    border: 1px solid #000;
                    border-collapse: collapse;
                    width: 100%;
                }
                .table-border td {
                    border: 1px solid #000;
                }
                .bold {
                    font-weight: bold;
                    font-size: 18px;
                }
                .yearMonthDay span {
                    padding: 0 20px;
                }
            </style>
        </head>
        <body>
            <table class="table-border" style="width: 100%">
        
                <!-- 第一部分：直赔须知标题和内容合并为一个单元格 -->
                <tr >
                    <td colspan="2" style="padding-bottom: 60px;">
                        <h2 style="text-align: center; font-weight: bold;padding: 10px 0;">
                            直赔须知
                        </h2>
        
                        <div class="p1">
                            <p>
                                {$emptySpace}1、申请条件：交通事故当事人选择道路事故人伤绿色通道医疗救助救治直赔服务必须市机动车交通事故造成人员受伤的，符合法律规定及保险合同约定，且无核定约定的免责事项或符合江苏省道路交通事故社会救助基金垫付条件的。
                            </p>
                            <p>{$emptySpace}2、申请人可以是通过交通事故伤者本人或者监护人（需提供委托书），使权人或被保险人。</p>
                            <p>{$emptySpace}3、申请人可以通过人伤绿色通道医疗救助服务中心、公安交管部门、保险公司人伤直赔服务窗口、道路救助基金委员会等相关机构办理绿色通道医疗救治直赔服务。</p>
                            <p>{$emptySpace}4、通过交通事故人伤绿色通道医疗救治直赔所需材料，可通过道路交通事故人伤绿色通道医疗救治直赔服务中心工作人员代为收取。</p>
                            <p>{$emptySpace}5、保险公司按照事故责任比例，中国家基本医疗保险标准范围内的合理的医疗费用赔偿医疗机构；对未纳入基本医疗保险标准范围内的医疗费用，保险公司将根据中国家基本医疗保险标准范围内的合理的医疗费用标准进行审核，对未经清的、合理的但不在基本医疗保险标准范围内的合理的医疗费用再次申请直赔，保险公司将对按照事故责任比例的直赔标准进行赔付。</p>
                            <p>{$emptySpace}6、对于中国家基本医疗保险标准范围外的医疗费用由事故各方按照相关法律规定，自行协商承担，并不得通过任何渠道向保险公司主张。</p>
                            <p>{$emptySpace}7、对于通过绿色通道医疗救助救治直赔服务中心进行直赔，对于直赔清单内，经医疗费用审核后，如有未通过审核的医疗费用，伤者无力承担的，本由医疗机构承担的医疗费用，如医疗机构同意，可由公安交管部门与医疗机构协商后，由公安交管部门垫付医疗费用。</p>
                        </div>
                    </td>
                </tr>
        
                <!-- 第二部分：本人已知晓（合并为一个单元格） -->
                <tr>
                    <td colspan="2" style="padding-left: 10px;">
                         <p class="bold">本人已知晓《道路交通事故人伤绿色通道医疗救助救治直赔申请书》内容，并自愿申请。</p>
                        <div style="margin-top: 50px;padding-bottom: 20px;">
                            <table style="width: 100%; border: none;">
                                <tr>
                                    <td style="width: 50%; border: none;position: relative;left: 100px;padding-left: 30px;margin:30px 0;padding-bottom: 20px;">
                                        <p>被保险人签字（公章）</p>
                                    </td>
                                    <td style="width: 50%; border: none;margin-right: 60px;">
                                       <p style="margin-bottom: 20px;">申请人签字：</p>
                                       <p class="yearMonthDay">日期： <span>年 </span><span>月 </span><span>日</span></p>
                                       </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="height: 200px; position: relative;">
                        <div style="position: absolute; top: 10px; left: 10px;">
                            <p style="padding-bottom: 30px;" class="bold">直赔服务中心意见</p>
                            <p>□门诊收据 □事故认定书 □司法文书和事故认和事故认定责任判决和事故认定责任判决定责任判决书（执法文书）强制</p>
                            <p>□公安交管部门伤者具体病历 □公安交管部门第三方赔付通知书</p>
                            <p>□其他证明 _______ 公司 _______ 工作人员</p>
                        </div>
                        <div style="position: absolute; bottom: 10px; right:100px;">
                            <p style="margin-bottom: 30px;">经办人：</p>
                            <p class="yearMonthDay">日期： <span>年 </span><span>月 </span><span>日</span></p>
                        </div>
                    </td>
                </tr>
            </table>
        </body>
        </html>
HTML;
        

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        //保存pdf
        $output = $dompdf->output();
        $filename = $directCompensationId.'_signature.pdf';

        //保存到指定目录 放到 stroage/uploads/{日期}/direct_compensation/
        $path = BASE_PATH.'/storage/export/'.date('Y-m-d').'/merge_signature/';


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
        $url2 = '/export/'.date('Y-m-d').'/merge_signature/' . $filename;
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

        return $url;
    }
}

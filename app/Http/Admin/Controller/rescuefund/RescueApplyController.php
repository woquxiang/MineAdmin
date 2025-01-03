<?php

namespace App\Http\Admin\Controller\rescuefund;

use App\Service\rescuefund\RescueApplyService as Service;
use App\Http\Admin\Request\rescuefund\RescueApplyRequest as Request;
use Hyperf\Swagger\Annotation as OA;
use App\Http\Admin\Controller\AbstractController;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Result;
use App\Http\CurrentUser;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use Mine\Access\Attribute\Permission;
use Hyperf\HttpServer\Annotation\Middleware;
use Mine\Swagger\Attributes\ResultResponse;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Put;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\Delete;



#[OA\Tag('直赔申请书')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class RescueApplyController extends AbstractController
{
    public function __construct(
        private readonly Service $service,
        private readonly CurrentUser $currentUser
    ) {}

    #[Get(
        path: '/admin/rescuefund/rescue_apply/list',
        operationId: 'rescuefund:rescue_apply:list',
        summary: '直赔申请书列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['直赔申请书'],
    )]
    #[Permission(code: 'rescuefund:rescue_apply:list')]
    public function pageList(): Result
    {
        return $this->success(
            $this->service->page(
                $this->getRequestData(),
                $this->getCurrentPage(),
                $this->getPageSize()
            )
        );
    }

    #[Post(
        path: '/admin/rescuefund/rescue_apply',
        operationId: 'rescuefund:rescue_apply:create',
        summary: '新增直赔申请书',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['直赔申请书'],
    )]
    #[Permission(code: 'rescuefund:rescue_apply:create')]
    #[ResultResponse(instance: new Result())]
    public function create(Request $request): Result
    {
        $result = $this->service->create(array_merge($request->all(), [
            'created_by' => $this->currentUser->id(),
        ]));
        $result = $this->createPdf(json_decode(json_encode($result),true));
        //根据开发环境还是生产环境返回pdf域名+访问路径
        if(env('APP_ENV') == 'dev'){
            $result = 'http://127.0.0.1:9501'.$result;
        }else{
            $result = 'https://dev.ycjjwx.com'.$result;
        }
        return $this->success($result);
    }
    //dompdf生成路救申请书pdf
    public function createPdf($data)
    {
        //生成pdf路径/storage/年-月-日/road_apply/没有目录就创建
        $outputPath = BASE_PATH . '/storage/export/'.date('Y-m-d').'/road_apply/';
        if (!is_dir($outputPath)) {
            mkdir($outputPath, 0777, true);
        }
        $outputPath = $outputPath . date('Ymd').'_'.$data['application_id'].'_'.$data['id'].'.pdf';
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true); // 启用远程资源加载
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'SimSun');
        $dompdf = new \Dompdf\Dompdf($options);
        $imageUrl = BASE_PATH . '/storage/roadlogo.png';
        //$imageUrl = 'https://dev.ycjjwx.com/prod/uploads/2024-12-24/1ba26ec0-fabe-4d9a-a04e-227984c03271.png';
        $imageData = file_get_contents($imageUrl);
        // 检查是否成功获取内容
        if ($imageData === false) {
            throw new Exception("无法获取图片内容。请检查 URL 是否正确或网络是否畅通。");
        }
        // 获取图片的 MIME 类型（可以通过 getimagesize 获取）
        $imageInfo = getimagesizefromstring($imageData);
        if ($imageInfo === false) {
            throw new Exception("无法解析图片信息。请确认 URL 是否指向一张图片。");
        }
        $mimeType = $imageInfo['mime']; // 例如 image/png
        // 转换为 Base64 编码
        $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        $html = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
            <style>
                body {
                    font-family: SimSun, sans-serif;
                }
                h1{
                    width: 100%;
                    text-align: center;
                }
                .suojin{
                    text-indent: 2em;
                }
                .fujian{
                    text-indent: 2em;
                    margin-left: 2em;
                    border: 1px solid black;
                    padding: 1em;
                }
                .right{
                    text-align: right;
                }
                h2{
                    width: 100%;
                    text-align: center;
                }
                .beizhu{
                    text-indent: 3em;
                    font-size: 0.7rem;
                }
                .word-break {
                    word-break: break-word;
                    line-height: 1.5;
                }
                input[type="checkbox"] {
                    margin-right: 0.5em; /* 调整右边距 */
                    margin-left: 2rem;
                    vertical-align: middle; /* 垂直对齐 */
                    position: relative;
                    top:0.2rem;
                }
                img {
                    position: fixed; /* 固定定位 */
                    top: -10px;         /* 距离顶部 0 */
                    left: 0;        /* 距离左侧 0 */
                    z-index: 1000;  /* 确保在最上层显示 */
                    width: 100px;   /* 设置图片宽度（可选） */
                    height: auto;   /* 保持宽高比（可选） */
                }
            </style>
        </head>
        <body>
            <img src="'.$base64Image.'">
            <h1>救助基金垫付通知书</h1>
            <p>道路交通事故救助基金'.$data['acceptPoint'].'服务站（受理网点）：</p>
            <p class="suojin word-break">'.explode('-',$data['accident_date'])[0].'年'.explode('-',$data['accident_date'])[1].'月'.explode(' ',explode('-',$data['accident_date'])[2])[0].'日'.explode(':',explode(' ',explode('-',$data['accident_date'])[2])[1])[0].'时，我辖区'.$data['road'].'发生一起交通事故。该事故造成'.$data['injured'].'受伤，现伤者在'.($data['type']==1?'医院':'殡仪馆').',急需垫付'.($data['type']==1?'抢救费用':'丧葬费用').',依据《江苏省道路交通事故社会救助基金管理办法》及相关规定，望贵方尽快审核。</p>
            <p class="suojin">垫付原因（勾选）</p>
            <p><input type="checkbox" '.($data['reason']==1?'checked="true"':'').' >抢救费用超过交强险责任限额的;</p>
            <p><input type="checkbox" '.($data['reason']==2?'checked="true"':'').'>肇事机动车未参加交强险的;</p>
            <p><input type="checkbox" '.($data['reason']==3?'checked="true"':'').'>机动车肇事后逃逸的。</p>
            <p class="suojin">特此通知。</p>
            <p class="suojin">附件：</p>
            <p class="fujian word-break">'.$data['desc'].'</p>
            <p class="right">公安机关交通管制部门 （盖章）</p>
            <p class="right">联系人：'.$data['relation_name'].'</p>
            <p class="right">联系电话：'.$data['relation_phone'].'</p>
            <p class="right">'.explode('-',$data['date'])[0].'年'.explode('-',$data['date'])[1].'月'.explode('-',$data['date'])[2].'日</p>
            <h2>紫金保险道路救助基金服务中心 制</h2>
            <p class="beizhu">注：1、在申请数助基金过程中无害向任何人、任机构缴的费用。</p>
            <p class="beizhu" style="text-indent: 4.4em;">2、基金管理人未委托第三万机构或个人代为办理款助基金申请(医构与机构外)。</p>
            <p class="beizhu" style="text-indent: 4.4em;">3、有任何疑问或投诉、均可拨打统一客服热线(025)96019:关注信公众号:江苏省道路救助基金。</p>
        </body>
        </html>
        ';
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        $output = $dompdf->output();
    
        if (file_put_contents($outputPath, $output) === false) {
            throw new \RuntimeException('无法写入 PDF 文件到指定路径！');
        }
        //返回根目录pdf路径
        $pdfPath = '/export/'.date('Y-m-d').'/road_apply/'.date('Ymd').'_'.$data['application_id'].'_'.$data['id'].'.pdf';
        return $pdfPath;
    }

    #[Put(
        path: '/admin/rescuefund/rescue_apply/{id}',
        operationId: 'rescuefund:rescue_apply:update',
        summary: '保存直赔申请书',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['直赔申请书'],
    )]
    #[Permission(code: 'rescuefund:rescue_apply:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, Request $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/rescuefund/rescue_apply',
        operationId: 'rescuefund:rescue_apply:delete',
        summary: '删除直赔申请书',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['直赔申请书'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'rescuefund:rescue_apply:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

}

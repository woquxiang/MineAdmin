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

namespace App\Http\Api\Controller\V1;

use App\Exception\BusinessException;
use App\Http\Api\Request\V1\UserRequest;
use App\Http\Api\Middleware\TokenMiddleware;
use App\Http\Common\Controller\AbstractController;
use App\Http\Common\Result;
use App\Http\Common\ResultCode;
use App\Http\CurrentUser;
use App\Service\PassportService;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Service\V1\MemberService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: TokenMiddleware::class, priority: 100)]
final class MemberController extends AbstractController
{
    public function __construct(
        private readonly CurrentUser $currentUser,
        private readonly MemberService $service
    ) {}

    #[Post(
        path: '/v1/member/updateIdCard',
    )]
    public function updateIdCard(RequestInterface $request): Result
    {
        $idCardName = $request->post('id_card_name');
        $idCardNumber = $request->post('id_card_number');

        if(empty($idCardNumber) || empty($idCardName)){
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '参数错误');
        }

        try {
            $info = $this->service->updateIdCardInfo($idCardName, $idCardNumber);
            return $this->success($info,'身份证信息更新成功');
        } catch (\Exception $e) {
            return $this->error('更新失败');
        }
    }

    #[Post(
        path: '/v1/member/getInfo',
    )]
    public function getInfo(RequestInterface $request): Result
    {
        return $this->success($this->currentUser->user());
    }
}

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

namespace App\Http\Admin\Request;

use Hyperf\Collection\Arr;
use Hyperf\Swagger\Annotation\Property;
use Hyperf\Swagger\Annotation\Schema;
use Hyperf\Validation\Request\FormRequest;
use Mine\Support\Request\ClientIpRequestTrait;
use Mine\Support\Request\ClientOsTrait;
use Psr\Http\Message\ServerRequestInterface;

#[Schema(title: '登录请求', description: '登录请求参数', properties: [
    new Property('username', description: '用户名', type: 'string'),
    new Property('password', description: '密码', type: 'string'),
])]
class PassportLoginRequest extends FormRequest
{
    use ClientIpRequestTrait;
    use ClientOsTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|exists:user,username',
            'password' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => trans('user.username'),
            'password' => trans('user.password'),
        ];
    }

    public function ip(): string
    {
        return Arr::first($this->getClientIps(), static fn ($ip) => $ip, '0.0.0.0');
    }

    public function getRealClientIp(): string
    {
        $request = $this->container->get(ServerRequestInterface::class);

        // 获取 X-Real-IP 头部
        $realIp = $request->getHeaderLine('x-real-ip');

        if ($realIp) {
            return $realIp;
        }

        // 如果没有 X-Real-IP，检查 X-Forwarded-For 头部
        $forwardedFor = $request->getHeaderLine('x-forwarded-for');
        if ($forwardedFor) {
            // 取第一个 IP 地址
            return explode(',', $forwardedFor)[0];
        }

        // 如果都没有，返回 remote_addr
        return $request->getServerParams()['remote_addr'] ?? '0.0.0.0';
    }
}

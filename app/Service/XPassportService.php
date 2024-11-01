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

namespace App\Service;

use App\Exception\BusinessException;
use App\Exception\JwtInBlackException;
use App\Http\Common\ResultCode;
use App\Model\Enums\User\Type;
use App\Repository\Permission\UserRepository;
use EasyWeChat\MiniApp\Application;
use Hyperf\Collection\Arr;
use Hyperf\Stringable\Str;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\RegisteredClaims;
use Lcobucci\JWT\UnencryptedToken;
use Mine\Jwt\Factory;
use Mine\Jwt\JwtInterface;
use Mine\JwtAuth\Event\UserLoginEvent;
use Mine\JwtAuth\Interfaces\CheckTokenInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

final class XPassportService extends IService implements CheckTokenInterface
{
    /**
     * @var string jwt场景
     */
    private string $jwt = 'default';

    public function __construct(
        protected readonly UserRepository $repository,
        protected readonly Factory $jwtFactory,
        protected readonly EventDispatcherInterface $dispatcher,
        protected readonly ServerRequestInterface $request
    ) {}

    /**
     * @return array<string,int|string>
     */
    public function login(string $username, string $password, Type $userType = Type::SYSTEM, string $ip = '0.0.0.0', string $browser = 'unknown', string $os = 'unknown'): array
    {
        $user = $this->repository->findByUnameType($username, $userType);
        if (! $user->verifyPassword($password)) {
            $this->dispatcher->dispatch(new UserLoginEvent($user, $ip, $os, $browser, false));
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, trans('auth.password_error'));
        }
        $this->dispatcher->dispatch(new UserLoginEvent($user, $ip, $os, $browser));
        $jwt = $this->getJwt();
        return [
            'access_token' => $jwt->builderAccessToken((string) $user->id)->toString(),
            'refresh_token' => $jwt->builderRefreshToken((string) $user->id)->toString(),
            'expire_at' => (int) $jwt->getConfig('ttl', 0),
        ];
    }

    /**
     * @return array<string,int|string>
     */
    public function loginApi(string $username, string $password, Type $userType = Type::SYSTEM, string $ip = '0.0.0.0', string $browser = 'unknown', string $os = 'unknown'): array
    {
        try {
            $user = $this->repository->findByUnameType($username, $userType);
        }catch (\Throwable $e){
            throw new BusinessException( ResultCode::UNPROCESSABLE_ENTITY, '用户不存在');
        }

        if (! $user->verifyPassword($password)) {
            $this->dispatcher->dispatch(new UserLoginEvent($user, $ip, $os, $browser, false));
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, trans('auth.password_error'));
        }
        $this->dispatcher->dispatch(new UserLoginEvent($user, $ip, $os, $browser));
        $jwt = $this->getApiJwt();
        return [
            'access_token' => $jwt->builderAccessToken((string) $user->id)->toString(),
            'refresh_token' => $jwt->builderRefreshToken((string) $user->id)->toString(),
            'expire_at' => (int) $jwt->getConfig('ttl', 0),
        ];
    }

    public function loginWithMiniApp(string $code, string $ip = '0.0.0.0', string $browser = 'unknown', string $os = 'unknown'): array
    {
        // 通过 EasyWeChat 获取用户信息
        $config = config('easywechat');  // 获取配置
        $miniApp = new Application($config);

        try {
            $response = $miniApp->getClient()->get('/sns/jscode2session', [
                'appid' => $config['app_id'], // 小程序的 appid
                'secret' => $config['secret'], // 小程序的 app secret
                'js_code' => $code, // 获取到的 code
                'grant_type' => 'authorization_code',
            ])->toArray();

            if (isset($response['errcode'])) {
                throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY);
            }
        } catch (\Throwable $e) {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '微信登录失败');
        }

        $openid = $response['openid'];
        $user = $this->repository->findOrCreateByOpenid($openid);

        // 生成 JWT
        $this->dispatcher->dispatch(new UserLoginEvent($user, $ip, $os, $browser));
        $jwt = $this->getApiJwt();
        return [
            'access_token' => $jwt->builderAccessToken((string) $user->id)->toString(),
            'refresh_token' => $jwt->builderRefreshToken((string) $user->id)->toString(),
            'expire_at' => (int) $jwt->getConfig('ttl', 0),
        ];
    }

    public function getPhoneNumber(int $userId, $code): string
    {
        // 通过 EasyWeChat 获取用户信息
        $config = config('easywechat');  // 获取配置
        $miniApp = new Application($config);

        try {
            $response =  $miniApp->getClient()->postJson('wxa/business/getuserphonenumber', [
                'code'=>$code
            ])->toArray();

            if (isset($response['errcode'])) {
                throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY);
            }
        } catch (\Throwable $e) {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, '获取手机号失败');
        }
        $phoneNumber = $response['phone_info']['phoneNumber'];
        $this->repository->updatePhoneNumber($userId, $phoneNumber);

        return $phoneNumber;
    }

    public function getApiJwt(): JwtInterface{
        // 填写上一步的场景值
        return $this->jwtFactory->get('api');
    }

    public function checkJwt(UnencryptedToken $token): void
    {
        $this->getJwt()->hasBlackList($token) && throw new JwtInBlackException();
    }

    public function logout(UnencryptedToken $token): void
    {
        $this->getJwt()->addBlackList($token);
    }

    public function getJwt(): JwtInterface
    {
        return $this->jwtFactory->get($this->jwt);
    }

    /**
     * @return array<string,int|string>
     */
    public function refreshToken(UnencryptedToken $token): array
    {
        return value(static function (JwtInterface $jwt) use ($token) {
            $jwt->addBlackList($token);
            return [
                'access_token' => $jwt->builderAccessToken($token->claims()->get(RegisteredClaims::ID))->toString(),
                'refresh_token' => $jwt->builderRefreshToken($token->claims()->get(RegisteredClaims::ID))->toString(),
                'expire_at' => (int) $jwt->getConfig('ttl', 0),
            ];
        }, $this->getJwt());
    }

    protected function _getToken(ServerRequestInterface $request): string
    {
        if ($request->hasHeader('Authorization')) {
            return Str::replace('Bearer ', '', $request->getHeaderLine('Authorization'));
        }
        if ($request->hasHeader('token')) {
            return $request->getHeaderLine('token');
        }
        if (Arr::has($request->getQueryParams(), 'token')) {
            return $request->getQueryParams()['token'];
        }
        return '';
    }

    public function _getApiUserId()
    {
        try {
            $parsedToken = $this->getApiJwt()->parserAccessToken($this->_getToken($this->request));

            // 使用 claims() 方法来获取 claims
            $claims = $parsedToken->claims();

            return (int) $claims->get(RegisteredClaims::ID) ?? null; // 用户角色
        } catch (\Exception $e) {
            return null;
        }
    }
}

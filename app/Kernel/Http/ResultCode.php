<?php

namespace App\Kernel\Http;

use Hyperf\Constants\Annotation\Constants;
use Hyperf\Constants\Annotation\Message;
use Hyperf\Constants\ConstantsTrait;

#[Constants]
enum ResultCode: int
{
    use ConstantsTrait;

    #[Message('成功')]
    case SUCCESS = 200;

    #[Message('失败')]
    case FAIL = 500;

    #[Message('未登录')]
    case UNAUTHORIZED = 401;

    #[Message('禁止访问')]
    case FORBIDDEN = 403;

    #[Message('未找到')]
    case NOT_FOUND = 404;

    #[Message('方法不允许')]
    case METHOD_NOT_ALLOWED = 405;

    #[Message('不可接受')]
    case NOT_ACCEPTABLE = 406;
}
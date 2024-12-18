<?php

namespace App\Http\Admin\Controller\websocket;

use App\Service\accident\J123AccidentNumberService as Service;
use App\Http\Admin\Request\accident\J123AccidentNumberRequest as Request;
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
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\WebSocketServer\Context;
use Hyperf\Engine\WebSocket\Frame;
use Hyperf\Engine\WebSocket\Response;
use Hyperf\WebSocketServer\Constant\Opcode;


#[OA\Tag('案件号管理')]
#[OA\HyperfServer('http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]

class VideoController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    // 当有消息时处理信令
    public function onMessage($server, $frame): void
    {
        $data = json_decode($frame->data, true);

        switch ($data['type']) {
            case 'offer':
                $this->handleOffer($server, $frame, $data);
                break;
            case 'answer':
                $this->handleAnswer($server, $frame, $data);
                break;
            case 'candidate':
                $this->handleCandidate($server, $frame, $data);
                break;
            default:
                break;
        }
    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        var_dump('closed');
    }

    public function onOpen($server, $request): void
    {
        $response = (new Response($server))->init($request);
        // 使用 JSON 格式的数据推送
        $response->push(new Frame(payloadData: json_encode([
            'type' => 'connection',
            'message' => 'Connection opened'
        ])));
    }


    // 处理 offer 消息
    private function handleOffer($server, $frame, $data)
    {
        // 获取目标用户的 fd
        $targetFd = $data['targetFd'];
        $response = (new Response($server))->init($frame);
        $response->push(new Frame(payloadData: json_encode([
            'type' => 'offer',
            'offer' => $data['offer'],
            'targetFd' => $frame->fd,
        ])), $targetFd);
    }

    // 处理 answer 消息
    private function handleAnswer($server, $frame, $data)
    {
        $targetFd = $data['targetFd'];
        $response = (new Response($server))->init($frame);
        $response->push(new Frame(payloadData: json_encode([
            'type' => 'answer',
            'answer' => $data['answer'],
            'targetFd' => $frame->fd,
        ])), $targetFd);
    }

    // 处理 ICE candidate 消息
    private function handleCandidate($server, $frame, $data)
    {
        $targetFd = $data['targetFd'];
        $response = (new Response($server))->init($frame);
        $response->push(new Frame(payloadData: json_encode([
            'type' => 'candidate',
            'candidate' => $data['candidate'],
            'targetFd' => $frame->fd,
        ])), $targetFd);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: evalor
 * Date: 2018-11-28
 * Time: 19:09
 */

namespace App\WebSocket\Controller;

use App\Task\BroadcastTask;
use App\Utility\AppConst;
use App\WebSocket\ResponsePayload;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\Socket\Client\WebSocket as WebSocketClient;

/**
 * 消息广播
 * Class Broadcast
 * @package App\WebSocket\Controller
 */
class Broadcast extends BaseController
{
    /**
     * 广播需要通过验证之后才能执行
     * @param string|null $actionName
     * @return bool
     */
    function onRequest(?string $actionName): bool
    {
        $success = parent::onRequest($actionName);
        if ($success) {
            /** @var WebSocketClient $client */
            $client = $this->caller()->getClient();
            $userIsAdmin = $this->getRedis()->hGet(AppConst::REDIS_ONLINE_KEY, $client->getFd());
            if ($userIsAdmin) {
                return true;
            } else {
                $message = new ResponsePayload;
                $message->setStatus(ResponsePayload::STATUS_FAIL);
                $message->setAction(ResponsePayload::ACTION_AUTH);
                $message->setReason('access denied');
                $this->response()->setMessage($message);
                return false;
            }
        }
        return false;
    }

    /**
     * 广播给全体在线的客户端
     * @return void
     */
    function broadcast()
    {
        /** @var WebSocketClient $client */
        $client = $this->caller()->getClient();
        $broadcastPayload = $this->caller()->getArgs();
        if (!empty($broadcastPayload)) {
            TaskManager::async(new BroadcastTask(['payload' => $broadcastPayload, 'fromFd' => $client->getFd()]));
            $message = new ResponsePayload;
            $message->setStatus(ResponsePayload::STATUS_SUCCESS);
            $message->setAction(ResponsePayload::ACTION_BROADCAST_SEND);
            $message->setReason('broadcast to all client start');
            $this->response()->setMessage($message);
        } else {
            $message = new ResponsePayload;
            $message->setStatus(ResponsePayload::STATUS_FAIL);
            $message->setAction(ResponsePayload::ACTION_BROADCAST_SEND);
            $message->setReason('broadcast payload is empty');
            $this->response()->setMessage($message);
        }
    }
}
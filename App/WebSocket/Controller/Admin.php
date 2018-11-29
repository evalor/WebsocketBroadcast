<?php
/**
 * Created by PhpStorm.
 * User: evalor
 * Date: 2018-11-28
 * Time: 20:06
 */

namespace App\WebSocket\Controller;

use App\Utility\AppConst;
use App\WebSocket\ResponsePayload;
use EasySwoole\Socket\Client\WebSocket as WebSocketClient;

/**
 * 权限验证
 * Class Admin
 * @package App\WebSocket\Controller
 */
class Admin extends BaseController
{
    function auth()
    {
        $authPass = 'admin';
        $args = $this->caller()->getArgs();
        if (isset($args['auth']) && trim($args['auth']) === $authPass) {
            /** @var WebSocketClient $client */
            $client = $this->caller()->getClient();
            $this->getRedis()->hSet(AppConst::REDIS_ONLINE_KEY, $client->getFd(), true);
            $message = new ResponsePayload;
            $message->setAction(ResponsePayload::ACTION_AUTH);
            $message->setStatus(ResponsePayload::STATUS_SUCCESS);
            $this->response()->setMessage($message);
        } else {
            $message = new ResponsePayload;
            $message->setAction(ResponsePayload::ACTION_AUTH);
            $message->setStatus(ResponsePayload::STATUS_FAIL);
            $this->response()->setMessage($message);
        }
    }
}
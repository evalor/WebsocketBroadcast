<?php
/**
 * Created by PhpStorm.
 * User: evalor
 * Date: 2018-11-28
 * Time: 19:08
 */

namespace App\WebSocket;

use EasySwoole\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Socket\Bean\Caller;
use EasySwoole\Socket\Bean\Response;
use EasySwoole\Socket\Client\WebSocket as WebSocketClient;

class WebSocketParser implements ParserInterface
{
    /**
     * 解码上来的消息
     * @param string          $raw 消息内容
     * @param WebSocketClient $client 当前的客户端
     * @return Caller|null
     */
    public function decode($raw, $client): ?Caller
    {
        // 发送广播 { "class": "broadcast" , "action" : "broadcast" , "params": { "aa" : "aa" } }
        // 认证鉴权 { "class": "admin" , "action" : "auth" , "params": { "auth" : "admin" } }
        if ($raw !== 'PING') {
            $payload = json_decode($raw, true);
            $class = isset($payload['class']) ? $payload['class'] : 'index';
            $action = isset($payload['action']) ? $payload['action'] : 'actionNotFound';
            $params = isset($payload['params']) ? $payload['params'] : [];

            // 判断控制器是否存在 如果不存在则仍然是跳转到index控制器中
            $controllerClass = "\\App\\WebSocket\\Controller\\" . ucfirst($class);
            if (!class_exists($controllerClass)) $controllerClass = "\\App\\WebSocket\\Controller\\Index";

            // 设置一个Caller 返回给框架进行跳转
            $caller = new Caller;
            $caller->setClient($caller);
            $caller->setControllerClass($controllerClass);
            $caller->setAction($action);
            $caller->setArgs($params);
            return $caller;
        }
        return null;
    }

    /**
     * 打包下发的消息
     * @param Response        $response 控制器返回的响应
     * @param WebSocketClient $client 当前的客户端
     * @return string|null
     */
    public function encode(Response $response, $client): ?string
    {
        return $response->getMessage();
    }
}
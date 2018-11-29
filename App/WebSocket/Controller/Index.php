<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2018-11-28
 * Time: 19:58
 */

namespace App\WebSocket\Controller;

use App\WebSocket\ResponsePayload;
use EasySwoole\Socket\AbstractInterface\Controller;

/**
 * 默认的控制器
 * Class Index
 * @package App\WebSocket\Controller
 */
class Index extends Controller
{
    /**
     * 找不到方法
     * @param string|null $actionName
     */
    protected function actionNotFound(?string $actionName)
    {
        $message = new ResponsePayload;
        $message->setStatus(ResponsePayload::STATUS_FAIL);
        $message->setAction(ResponsePayload::ACTION_NOT_FOUND);
        $this->response()->setMessage($message);
    }
}
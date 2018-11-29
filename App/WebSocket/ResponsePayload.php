<?php
/**
 * Created by PhpStorm.
 * User: evalor
 * Date: 2018-11-28
 * Time: 20:48
 */

namespace App\WebSocket;

use EasySwoole\Spl\SplBean;

class ResponsePayload extends SplBean
{
    const STATUS_FAIL = 'FAIL';       // 成功状态
    const STATUS_SUCCESS = 'SUCCESS'; // 失败状态

    const ACTION_AUTH = 'AUTH';
    const ACTION_ERROR = 'ERROR';
    const ACTION_NOT_FOUND = 'NOT_FOUND';
    const ACTION_BROADCAST_MSG = 'BROADCAST_MSG';
    const ACTION_BROADCAST_SEND = 'BROADCAST_SEND';
    const ACTION_BROADCAST_FINISH = 'BROADCAST_FINISH';

    protected $status;
    protected $action;
    protected $reason;
    protected $params;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action): void
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params): void
    {
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param mixed $reason
     */
    public function setReason($reason): void
    {
        $this->reason = $reason;
    }


}
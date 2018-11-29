<?php
/**
 * Created by PhpStorm.
 * User: evalor
 * Date: 2018-11-28
 * Time: 20:10
 */

namespace App\WebSocket\Controller;

use App\Utility\Pool\RedisPool;
use App\Utility\Pool\RedisPoolObject;
use App\WebSocket\ResponsePayload;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Socket\AbstractInterface\Controller;

class BaseController extends Controller
{
    /** @var RedisPoolObject $redis */
    protected $redis;

    /**
     * 请求到来时获取Redis链接
     * @param string|null $actionName
     * @return bool
     */
    protected function onRequest(?string $actionName): bool
    {
        $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
        if ($redis) {
            $this->redis = $redis;
            return parent::onRequest($actionName);
        } else {
            $message = new ResponsePayload;
            $message->setStatus(ResponsePayload::STATUS_FAIL);
            $message->setAction(ResponsePayload::ACTION_ERROR);
            $message->setReason('redis pool is empty');
            $this->response()->setMessage($message);
            return false;
        }
    }

    /**
     * 请求结束时释放链接
     * @param string|null $actionName
     */
    function afterAction(?string $actionName)
    {
        if ($this->redis instanceof RedisPoolObject) {
            PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($this->redis);
        }
        parent::afterAction($actionName);
    }

    /**
     * 获取Redis链接
     * @return RedisPoolObject
     */
    function getRedis()
    {
        return $this->redis;
    }
}
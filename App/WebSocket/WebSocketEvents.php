<?php
/**
 * Created by PhpStorm.
 * User: evalor
 * Date: 2018-11-28
 * Time: 19:08
 */

namespace App\WebSocket;

use App\Utility\AppConst;
use App\Utility\Pool\RedisPool;
use App\Utility\Pool\RedisPoolObject;
use EasySwoole\Component\Pool\PoolManager;

class WebSocketEvents
{
    /**
     * 链接打开时 将用户的FD存入Redis
     * @param \swoole_server       $server
     * @param \swoole_http_request $req
     * @throws \Exception
     */
    static function onOpen(\swoole_server $server, \swoole_http_request $req)
    {
        $redisPool = PoolManager::getInstance()->getPool(RedisPool::class);
        $redis = $redisPool->getObj();
        if ($redis instanceof RedisPoolObject) {
            echo "websocket user {$req->fd} was connected\n";
            $redis->hSet(AppConst::REDIS_ONLINE_KEY, $req->fd, false);
            $redisPool->recycleObj($redis);
        } else {
            throw new \Exception('redis pool is empty');
        }
    }

    /**
     * 链接关闭时 将用户的FD从Redis删除
     * @param \swoole_server $server
     * @param int            $fd
     * @param int            $reactorId
     * @throws \Exception
     */
    static function onClose(\swoole_server $server, int $fd, int $reactorId)
    {
        $redisPool = PoolManager::getInstance()->getPool(RedisPool::class);
        $redis = $redisPool->getObj();
        if ($redis instanceof RedisPoolObject) {
            echo "websocket user {$fd} was close\n";
            $redis->hDel(AppConst::REDIS_ONLINE_KEY, $fd);
            $redisPool->recycleObj($redis);
        } else {
            throw new \Exception('redis pool is empty');
        }
    }

    /**
     * 清空在线用户列表
     * @throws \Exception
     */
    static function cleanOnlineUser()
    {
        $redisPool = PoolManager::getInstance()->getPool(RedisPool::class);
        $redis = $redisPool->getObj();
        if ($redis instanceof RedisPoolObject) {
            $redis->del(AppConst::REDIS_ONLINE_KEY);
            $redisPool->recycleObj($redis);
        } else {
            throw new \Exception('redis pool is empty');
        }
    }
}
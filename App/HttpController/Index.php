<?php

namespace App\HttpController;

use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Http\AbstractInterface\Controller;


/**
 * Class Index
 * @package App\HttpController
 */
class Index extends Controller
{
    /**
     * 首页方法
     * @author : evalor <master@evalor.cn>
     */
    function index()
    {
        $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
        $redis->set('testKey', 'testValue');
        $value = $redis->get('testKey');
        $redis->del('testKey');
        $this->response()->write($value);
        PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($redis);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/19
 * Time: 15:20
 */

namespace Xjtuwangke\Aliyun\OCS;

use Illuminate\Session\CacheBasedSessionHandler;
use Illuminate\Support\ServiceProvider;
use Cache;
use Session;
use Memcached;
use Illuminate\Cache\MemcachedStore;
use Illuminate\Cache\Repository;
use Illuminate\Cache\MemcachedConnector;
use SessionHandlerInterface;
use Xjtuwangke\BugSnag\Exception;

class AliyunOCSServiceProvider extends ServiceProvider
{

    public function boot(){
        //扩展cache
        Cache::extend('ocs',function($app){
            $store = $this->getOCSMemcachedStore($app);
            return new Repository($store);
        });
        //扩展Session
        Session::extend('ocs',function($app){
            $minutes = $this->app['config']['session.lifetime'];
            $store = $this->getOCSMemcachedStore($app);
            $repository = new Repository( $store );
            return new CacheBasedSessionHandler( $repository , $minutes );
        });
    }

    /**
     * @param $app
     * @return MemcachedStore
     * @throws Exception
     */
    protected function getOCSMemcachedStore( $app ){
        if( ! ini_get( 'memcached.use_sasl') ){
            throw new Exception('php.ini设置中没有开启memcached.use_sasl');
        }
        $user = $app['config']['cache.stores.ocs.user'];
        $pass = $app['config']['cache.stores.ocs.pass'];

        $memcached = new Memcached();
        $memcached->setOption(Memcached::OPT_COMPRESSION, false); //关闭压缩功能
        $memcached->setOption(Memcached::OPT_BINARY_PROTOCOL, true); //使用binary二进制协议

        $memcached->setSaslAuthData($user, $pass); //设置OCS帐号密码进行鉴权

        $servers = $app['config']['cache.stores.ocs.servers'];
        foreach ($servers as $server) {
            $memcached->addServer(
                $server['host'], $server['port'], $server['weight']
            );
        }

        // 从配置文件中读取缓存前缀
        $prefix = $app['config']['cache.prefix'];
        // 创建 MemcachedStore 对象
        $store = new MemcachedStore($memcached, $prefix);
        return $store;
    }

    public function register(){

    }
}
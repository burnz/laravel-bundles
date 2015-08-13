<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/7
 * Time: 23:57
 */

namespace Xjtuwangke\Aliyun\MQS\ServiceProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider{

    public function boot(){
        /**
         * Extend闭包需要返回一个ConnectorInterface的Concrete
         * QueueManager内部调用ConnectorInterface::connect($config)得到Queue实例。
         * 一般为了简化类的数目,Queue同时也实现了ConnectorInterface
         * 在ServiceProvider中不再需要显式地配置连接。
         */
        \Queue::extend( 'aliyun-mqs' , function(){
            return new MQSQueue;
        });
    }

    public function register(){

    }
}
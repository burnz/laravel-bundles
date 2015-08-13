<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/7
 * Time: 23:39
 */

namespace Xjtuwangke\Aliyun\MQS\ServiceProvider;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Queue\Queue;
use Xjtuwangke\Aliyun\MQS\Client as MQSClient;
use \Illuminate\Config\Repository as Config;
use Xjtuwangke\Aliyun\MQS\MQSMessage;

/**
 * Class MQSQueue
 * Queue类维护一个到队列的连接，并将push,pop,release行为抽象出来
 * @package Xjtuwangke\Aliyun\MQS\ServiceProvider
 */
class MQSQueue extends Queue implements QueueContract , ConnectorInterface{

    /**
     * @var MQSClient MQS HttpClient 负责发送和解析MQS请求
     */
    protected $client = null;

    /**
     * @var string 对列名
     */
    protected $queue_name;

    /**
     * ConnectorInterface实现 返回一个Queue(就是自己)
     * @param array $config
     * @return $this
     */
    public function connect(array $config){
        $config = new Config( $config );
        $this->queue_name = $config->get( 'queue' , 'default' );
        $this->client = new MQSClient( $config , app('httpClient') , app('Illuminate\Contracts\Cache\Repository') , app('log') );
        return $this;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string  $job  SomeClass@fire
     * @param  mixed   $data  parameters
     * @param  string  $queue
     * @return mixed
     */
    public function push($job, $data = '', $queue = null){
        return $this->pushRaw($this->createPayload($job, $data), $queue);
    }

    /**
     * Create a payload string from the given job and data.
     *
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return string
     */
    protected function createPayload($job, $data = '', $queue = null){
        return parent::createPayload($job, $data);
    }

    /**
     * Push a raw payload onto the queue.
     * payload的实际例子
     * {"job":"Illuminate\\Queue\\CallQueuedHandler@call","data":{"..."}}
     * @param  string  $payload
     * @param  string  $queue
     * @param  array   $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = array() , $delay = null ){
        if( is_null( $queue ) ){
            $queue = $this->queue_name;
        }
        $this->client->sendMessage( $queue , $payload );
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTime|int  $delay
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null){
        if( $delay instanceof \DateTime ){
            $delay = $delay->getTimestamp() - Carbon::now()->getTimestamp();
        }
        $delay = (int) $delay;
        return $this->pushRaw( $this->createPayload($job, $data) , $queue , array() , $delay );
    }

    /**
     * Pop the next job off of the queue.
     * @param null $queue
     * @return MQSJob | null
     */
    public function pop($queue = null){
        if( is_null( $queue ) ){
            $queue = $this->queue_name;
        }
        $message = $this->client->receiveMessage( $queue );
        if( ! is_null( $message ) ){
            return new MQSJob( $this->container , $queue , $this , $this->client , $message );
        }
        else{
            return null;
        }
    }

    /**
     * 任务队列删除已经完成的message
     * @param null       $queue
     * @param MQSMessage $message
     */
    public function deleteReserved( $queue = null , MQSMessage $message ){
        if( is_null( $queue ) ){
            $queue = $this->queue_name;
        }
        $this->client->deleteMessage( $queue , $message->getReceiptHandle() );
    }

    /**
     * 重新放回队列
     * @param null       $queue
     * @param MQSMessage $message
     * @param            $delay
     */
    public function release( $queue = null , MQSMessage $message , $delay ){
        if( is_null( $queue ) ){
            $queue = $this->queue_name;
        }
        if( $delay < 1 ){
            $delay = 1;
        }
        $this->client->changeMessageVisibility( $queue , $message->getReceiptHandle() , $delay );
    }
}
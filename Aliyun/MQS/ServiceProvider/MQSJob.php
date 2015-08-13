<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/8
 * Time: 01:15
 */

namespace Xjtuwangke\Aliyun\MQS\ServiceProvider;

use Illuminate\Container\Container;
use \Illuminate\Contracts\Queue\Job as JobContract;
use \Illuminate\Queue\Jobs\Job;
use Xjtuwangke\Aliyun\MQS\Client;
use Xjtuwangke\Aliyun\MQS\MQSMessage;

/**
 * Class MQSJob
 * Job类抽象出Queue中的一条message。操作包括fire,delete,release。其中delete和release需要访问对应的Queue实例接口
 * @package Xjtuwangke\Aliyun\MQS\ServiceProvider
 */
class MQSJob extends Job implements JobContract{

    /**
     * @var string
     */
    protected $queue_name;

    /**
     * @var MQSQueue
     */
    protected $queue;

    /**
     * @var Client
     */
    protected $mqsClient;

    /**
     * @var MQSMessage
     */
    protected $message;

    public function __construct( Container $container , $queue_name , MQSQueue $queue , Client $mqsClient , MQSMessage $message ){
        $this->container = $container;
        $this->queue_name = $queue_name;
        $this->queue = $queue;
        $this->mqsClient = $mqsClient;
        $this->message = $message;
    }

    /**
     * Fire the job.
     *
     * @return void
     */
    public function fire()
    {
        $this->resolveAndFire(json_decode($this->message->getMessageBody(), true));
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();
        $this->queue->deleteReserved( $this->queue_name, $this->message );
    }

    /**
     * Release the job back into the queue.
     *
     * @param  int  $delay
     * @return void
     */
    public function release($delay = 0)
    {
        parent::release($delay);

        parent::delete();

        $this->queue->release($this->queue_name, $this->message, $delay );
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return (int) $this->message->getDequeueCount();
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->message->getMessageBody();
    }

    /**
     * Get the IoC container instance.
     *
     * @return \Illuminate\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return MQSQueue
     */
    public function getMQSQueue()
    {
        return $this->queue;
    }

}
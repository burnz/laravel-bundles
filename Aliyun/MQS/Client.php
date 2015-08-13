<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/7
 * Time: 14:50
 */

namespace Xjtuwangke\Aliyun\MQS;

use Xjtuwangke\Aliyun\MQS\Exceptions\MQSEmptyMessageException;
use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;
use Xjtuwangke\HttpClient\HTTPApiClient\HTTPApiClient;

/**
 * Class Client
 * @link http://docs.aliyun.com/?spm=5176.7393424.9.4.ZLyiP5#/pub/mqs/api_reference/intro&intro
 * @package Xjtuwangke\Aliyun\MQS
 */
class Client extends HTTPApiClient{

    protected $access_key		= '';
    protected $access_secret	= '';
    protected $queue_owner_id	= '';
    protected $mqs_url			= '';

    protected $requestMiddleware = array(
        MQSRequestMiddleware::class
    );

    protected $responseMiddleware = array(
        MQSResponseMiddleware::class
    );

    /**
     * @return string
     */
    public function getAccessKey()
    {
        return $this->access_key;
    }

    /**
     * @return string
     */
    public function getAccessSecret()
    {
        return $this->access_secret;
    }

    /**
     * @return string
     */
    public function getQueueOwnerId()
    {
        return $this->queue_owner_id;
    }

    /**
     * @return string
     */
    public function getMqsUrl()
    {
        return $this->mqs_url;
    }


    /**
     *
     */
    protected function onConstruct(){
        $this->access_key	   = $this->config->get( 'key' );
        $this->access_secret   = $this->config->get( 'secret' );
        $this->queue_owner_id  = $this->config->get( 'queue_owner_id' );
        $this->mqs_url		   = $this->config->get( 'mqs_url' );
    }

    /**
     * 本接口用于创建一个新的消息队列。
     * 消息队列名称是一个不超过256个字符的字符串，必须以字母为首字符，剩余部分中可以包含字母、数字和横划线(-)组成。
     * @param             $queueName
     * @param QueueConfig $config
     * @return array
     */
    public function createQueue($queueName, QueueConfig $config = null ){
        if( is_null( $config ) ){
            $config = new QueueConfig();
        }
        $requestResource = "/" . $queueName;
        $request = new ClientRequest( $requestResource , ClientRequest::PUT );
        $request->setContent( $config->toQueueXML() );

        $response = $this->sendRequest( $request );
        $content = $response->getContent();
        return Util::getXmlData( $content );
    }

    /**
     * 修改消息队列的属性
     * DelaySeconds	发送到该Queue的所有消息默认将以DelaySeconds参数指定的秒数延后可被消费，单位为秒。	0-604800秒（7天）范围内某个整数值，默认值为0
     * MaximumMessageSize	发送到该Queue的消息体的最大长度，单位为byte。	1024(1K)-65536（64K）范围内的某个整数值，默认值为65536（64K）。
     * MessageRetentionPeriod	消息在该Queue中最长的存活时间，从发送到该队列开始经过此参数指定的时间后，不论消息是否被取出过都将被删除，单位为秒。	60 (1分钟)-1296000 (15 天)范围内某个整数值，默认值345600 (4 天)
     * VisibilityTimeout	消息从该Queue中取出后从Active状态变成Inactive状态后的持续时间，单位为秒。	1-43200(12小时)范围内的某个值整数值，默认为30（秒）
     * PollingWaitSeconds	当Queue消息量为空时，针对该Queue的ReceiveMessage请求最长的等待时间，单位为秒。	0-30秒范围内的某个整数值，默认为0（秒）
     * @param             $queueName
     * @param QueueConfig $config
     * @return array
     */
    public function setQueueAttributes( $queueName, QueueConfig $config = null ){

        if( is_null( $config ) ){
            $config = new QueueConfig();
        }
        $requestResource = "/" . $queueName . "?metaoverride=true";
        $request = new ClientRequest( $requestResource , ClientRequest::PUT );
        $request->setContent( $config->toQueueXML() );
        $response = $this->sendRequest( $request );
        $content = $response->getContent();
        return Util::getXmlData( $content );
    }

    /**
     * 获取消息队列的属性
     * @param $queueName
     * @return array
     */
    public function getQueueAttributes($queueName){
        $requestResource = "/" . $queueName;
        $request = new ClientRequest( $requestResource , ClientRequest::GET );
        $request->setContent( "" );
        $response = $this->sendRequest( $request );
        $content = $response->getContent();
        return Util::getXmlData( $content );
    }

    /**
     * 删除一个已创建的消息队列
     * @param $queueName
     * @return array
     */
    public function deleteQueue($queueName){
        $request = new ClientRequest( "/" . $queueName , ClientRequest::DELETE );
        $request->setContent( "" );
        $response = $this->sendRequest( $request );
        $content = $response->getContent();
        return Util::getXmlData( $content );
    }

    /**
     * 获取多个消息队列列表
     * @param string $prefix
     * @param string $number
     * @param string $marker
     * @return array
     */
    public function listQueue($prefix='',$number='',$marker=''){
        $request = new ClientRequest( "/" , ClientRequest::GET );
        $request->setHeader( 'x-mqs-prefix' , $prefix );
        $request->setHeader( 'x-mqs-ret-number' , $number );
        $request->setHeader( 'x-mqs-marker' , $marker );
        $request->setContent( "" );
        $response = $this->sendRequest( $request );
        $content = $response->getContent();
        return Util::getXmlData( $content );
    }

    /**
     * 发送消息到指定的消息队列
     * 本接口用于发送消息到指定的消息队列，普通消息发送到消息队列随即可被消费者消费。
     * 但是如果生产者发送一个消息不想马上被消费者消费（典型的使用场景为定期任务），生产者在发送消息时设置DelaySeconds参数就可以达到此目标。
     * 发送带DelaySeconds参数值大于0的消息初始状态为Delayed，
     * 此时消息不能被消费者消费，只有等DelaySeconds时间后消息变成Active状态后才可消费。
     * @param     $queueName
     * @param     $msgBody
     * @param int $delaySeconds
     * @param int $priority
     * @return array
     */
    public function sendMessage( $queueName , $msgBody , $delaySeconds = 0 , $priority = 8 ){
        $content = Util::generateMessageXML( $msgBody , $delaySeconds , $priority );
        $request = new ClientRequest( "/" . $queueName . "/messages" , ClientRequest::POST );
        $request->setContent( $content );
        $response = $this->sendRequest( $request );
        $content = $response->getContent();
        return Util::getXmlData( $content );
    }

    /**
     * 接收指定的队列消息
     * 本接口用于消费者消费消息队列的消息，ReceiveMessage操作会将取得的消息状态变成Inactive，
     * Inactive的时间长度由Queue属性VisibilityTimeout指定（详见CreateQueue接口）。
     * 消费者在VisibilityTimeout时间内消费成功后需要调用DeleteMessage接口删除该消息，否则该消息将会被重新置为Active，
     * 此消息又可被消费者重新消费。
     *
     * Response Body 返回的结果为XML格式，返回Message消息正文及消息属性。
     * 参数名称	说明
     * MessageId	消息编号，在一个Queue中唯一
     * ReceiptHandle	本次获取消息产生的临时句柄，用于删除和修改处于Inactive消息，NextVisibleTime之前有效。
     * MessageBody	消息正文
     * MessageBodyMD5	消息正文的MD5值
     * EnqueueTime	消息发送到队列的时间，从1970年1月1日0点整开始的毫秒数
     * NextVisibleTime	下次可被再次消费的时间，从1970年1月1日0点整开始的毫秒数
     * FirstDequeueTime	第一次被消费的时间，从1970年1月1日0点整开始的毫秒数
     * DequeueCount	总共被消费的次数
     * Priority	消息的优先级权值
     * @param $queue
     * @param $second
     *       如果ReceiveMessage请求附带waitseconds参数,
     *       则在Queue无消息时,此次ReceiveMessage请求进入到Polling等待时长为waitseconds；
     *       如果未设置waitseconds，
     *       则默认使用所属Queue的PollingWaitSeconds属性(参见CreateQueue接口)。
     * @return MQSMessage | null
     */
    public function receiveMessage( $queue , $second = null ){
        if( ! is_null( $second ) ){
            $request = new ClientRequest( "/" . $queue . "/messages?waitseconds=".$second , ClientRequest::GET );
        }
        else{
            $request = new ClientRequest( "/" . $queue . "/messages" , ClientRequest::GET );
        }
        $request->setContent( "" );
        try{
            $response = $this->sendRequest( $request );
            $content = $response->getContent();
            return new MQSMessage( Util::getXmlData( $content ) );
        }
        catch( MQSEmptyMessageException $e ){
            return null;
        }

    }

    /**
     * 本接口用于删除已经被消费过的消息，消费者需将上次消费后得到的ReceiptHandle 作为参数来定位要删除的消息。
     * 本操作只有在NextVisibleTime时刻之前执行才能成功；
     * 如果过了NextVisibleTime时刻，消息重新变回Active状态，ReceiptHandle就会失效，删除失败，需重新消费获取新的ReceiptHandle。
     * 删除已经被接收过的消息
     * @param $queueName
     * @param $receiptHandle
     * @return array
     */
    public function deleteMessage( $queueName , $receiptHandle ){
        $request = new ClientRequest( "/" . $queueName . "/messages?ReceiptHandle=".$receiptHandle , ClientRequest::DELETE );
        $request->setContent( "" );
        $response = $this->sendRequest( $request );
        $content = $response->getContent();
        return Util::getXmlData( $content );
    }

    /**
     * 查看消息，但不改变消息状态（是否被查看或接收）
     * 本接口用于消费者查看消息，PeekMessage与ReceiveMessage不同，
     * PeekMessage并不会改变消息的状态，即被PeekMessage获取消息后消息仍然处于Active状态，仍然可被查看或消费；
     * 而后者操作成功后消息进入Inactive，在VisibilityTimeout的时间内不可被查看和消费。
     * @param $queuename
     * @return MQSMessage
     */
    public function peekMessage( $queuename ){
        $request = new ClientRequest( "/" . $queuename . "/messages?peekonly=true" , ClientRequest::GET );
        $request->setContent( "" );
        $response = $this->sendRequest( $request );
        $content = $response->getContent();
        return new MQSMessage( Util::getXmlData( $content ) );
    }

    /**
     * 修改未被查看消息时间
     * 本接口用于修改被消费过并且还处于的Inactive的消息到下次可被消费的时间，成功修改消息的VisibilityTimeout后，返回新的ReceiptHandle。
     * @param $queueName
     * @param $receiptHandle
     * @param $visibilitytimeout
     * @return array
     */
    public function changeMessageVisibility( $queueName , $receiptHandle , $visibilitytimeout){
        $request = new ClientRequest( "/" . $queueName . "/messages?ReceiptHandle=". $receiptHandle . "&VisibilityTimeout=". $visibilitytimeout , ClientRequest::PUT );
        $request->setContent( "" );
        $response = $this->sendRequest( $request );
        $content = $response->getContent();
        return Util::getXmlData( $content );
    }
}
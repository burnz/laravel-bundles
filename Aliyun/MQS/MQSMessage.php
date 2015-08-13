<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/8
 * Time: 02:43
 */

namespace Xjtuwangke\Aliyun\MQS;


class MQSMessage {

    /**
     * MessageId	消息编号，在一个Queue中唯一
     * ReceiptHandle	本次获取消息产生的临时句柄，用于删除和修改处于Inactive消息，NextVisibleTime之前有效。
     * MessageBody	消息正文
     * MessageBodyMD5	消息正文的MD5值
     * EnqueueTime	消息发送到队列的时间，从1970年1月1日0点整开始的毫秒数
     * NextVisibleTime	下次可被再次消费的时间，从1970年1月1日0点整开始的毫秒数
     * FirstDequeueTime	第一次被消费的时间，从1970年1月1日0点整开始的毫秒数
     * DequeueCount	总共被消费的次数
     * Priority	消息的优先级权值
     */

    protected $attributes = array(
        'MessageId' => null ,
        'ReceiptHandle' => null ,
        'MessageBody' => null ,
        'MessageBodyMD5' => null ,
        'EnqueueTime' => null ,
        'NextVisibleTime' => null ,
        'FirstDequeueTime' => null ,
        'DequeueCount' => null ,
        'Priority' => null ,
    );

    protected $job;

    protected $data;

    public function __construct( array $attributes ){
        $this->attributes = array_merge( $this->attributes , $attributes );
    }

    public function getMessageId(){
        return $this->attributes['MessageId'];
    }

    public function getReceiptHandle(){
        return $this->attributes['ReceiptHandle'];
    }

    public function getMessageBody(){
        return base64_decode( $this->attributes['MessageBody'] );
    }

    public function getMessageBodyMD5(){
        return $this->attributes['MessageBodyMD5'];
    }

    public function getEnqueueTime(){
        return $this->attributes['EnqueueTime'];
    }

    public function getNextVisibleTime(){
        return $this->attributes['NextVisibleTime'];
    }

    public function getFirstDequeueTime(){
        return $this->attributes['FirstDequeueTime'];
    }

    public function getDequeueCount(){
        return $this->attributes['DequeueCount'];
    }

    public function getPriority(){
        return $this->attributes['Priority'];
    }

}
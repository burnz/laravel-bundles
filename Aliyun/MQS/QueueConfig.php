<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/7
 * Time: 17:26
 */

namespace Xjtuwangke\Aliyun\MQS;


class QueueConfig {

    protected $attributes = array(
        'DelaySeconds'=>0,
        'MaximumMessageSize'=>65536,
        'MessageRetentionPeriod'=>345600,
        'VisibilityTimeout'=>30,
        'PollingWaitSeconds'=>30
    );

    public function setDelaySeconds( $value ){
        $this->attributes['DelaySeconds'] = (int) $value;
        return $this;
    }

    public function setMaximumMessageSize( $value ){
        $this->attributes['MaximumMessageSize'] = (int) $value;
        return $this;
    }

    public function setMessageRetentionPeriod( $value ){
        $this->attributes['MessageRetentionPeriod'] = (int) $value;
        return $this;
    }

    public function setVisibilityTimeout( $value ){
        $this->attributes['VisibilityTimeout'] = (int) $value;
        return $this;
    }

    public function setPollingWaitSeconds( $value ){
        $this->attributes['PollingWaitSeconds'] = (int) $value;
        return $this;
    }

    public function __toArray(){
        return $this->attributes;
    }

    //数据转换到xml
    public function toQueueXML(){
        $dom = new \DOMDocument("1.0", "utf-8");
        $dom->formatOutput = TRUE;
        $root = $dom->createElement("Queue");//创建根节点
        $dom->appendchild($root);
        $price=$dom->createAttribute("xmlns");
        $root->appendChild($price);
        $priceValue = $dom->createTextNode('http://mqs.aliyuncs.com/doc/v1/');
        $price->appendChild($priceValue);
        foreach($this->attributes as $k=>$v){
            $queue = $dom->createElement($k);
            $root->appendChild($queue);
            $titleText = $dom->createTextNode($v);
            $queue->appendChild($titleText);
        }
        return $dom->saveXML();
    }

}
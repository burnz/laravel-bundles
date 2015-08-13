<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/7
 * Time: 16:30
 */

namespace Xjtuwangke\Aliyun\MQS;

use Carbon\Carbon;
use Overtrue\Wechat\Utils\XML;

class Util {

    /**
     * 签名函数
     * @param        $VERB
     * @param        $CONTENT_MD5
     * @param        $CONTENT_TYPE
     * @param        $GMT_DATE
     * @param array  $CanonicalizedMQSHeaders
     * @param string $CanonicalizedResource
     * @return string
     */
    public static function getSignature( $VERB, $CONTENT_MD5, $CONTENT_TYPE, $GMT_DATE, $CanonicalizedMQSHeaders = array(), $CanonicalizedResource = "/" , $key , $secret ){
        $order_keys = array_keys( $CanonicalizedMQSHeaders );
        sort( $order_keys );
        $x_mqs_headers_string = "";
        foreach( $order_keys as $k ){
            $x_mqs_headers_string .= join( ":", array( strtolower($k), $CanonicalizedMQSHeaders[ $k ] . "\n" ) );
        }
        $string2sign = sprintf(
            "%s\n%s\n%s\n%s\n%s%s",
            $VERB,
            $CONTENT_MD5,
            $CONTENT_TYPE,
            $GMT_DATE,
            $x_mqs_headers_string,
            $CanonicalizedResource
        );
        $sig = base64_encode(hash_hmac('sha1',$string2sign,$secret,true));
        return "MQS " . $key . ":" . $sig;
    }

    /**
     * 获取GMT时间
     * @return string
     */
    public static function getGMTDate(){
        return Carbon::now()->setTimezone( new \DateTimeZone( 'UTC' ) )->format("D, d M Y H:i:s") . " GMT";
    }

    /**
     * 解析xml
     * @param $strXml
     * @return array
     */
    public static function getXmlData($strXml){
        $pos = strpos($strXml, 'xml');
        if ($pos) {
            $xmlCode = simplexml_load_string($strXml,'SimpleXMLElement', LIBXML_NOCDATA);
            $arrayCode = static::get_object_vars_final($xmlCode);
            return $arrayCode;
        } else {
            return array();
        }
    }

    /**
     * 解析obj
     * @param $obj
     * @return array
     */
    public static function get_object_vars_final($obj){
        if(is_object($obj)){
            $obj=get_object_vars($obj);
        }
        if(is_array($obj)){
            foreach ($obj as $key=>$value){
                $obj[$key]= static::get_object_vars_final($value);
            }
        }
        return $obj;
    }

    /**
     * 数据转换到xml
     * @param     $msgbody
     * @param int $DelaySeconds
     * @param int $Priority
     * @return string
     */
    public static function generateMessageXML( $msgbody , $DelaySeconds = 0 , $Priority = 8 ){
        $dom = new \DOMDocument("1.0", "utf-8");
        $dom->formatOutput = TRUE;
        $root = $dom->createElement("Message");//创建根节点
        $dom->appendchild($root);
        $price=$dom->createAttribute("xmlns");
        $root->appendChild($price);
        $priceValue = $dom->createTextNode('http://mqs.aliyuncs.com/doc/v1/');
        $price->appendChild($priceValue);
        $msg=array('MessageBody'=>base64_encode( $msgbody ),'DelaySeconds'=>$DelaySeconds,'Priority'=>$Priority);
        foreach($msg as $k=>$v){
            $msg = $dom->createElement($k);
            $root->appendChild($msg);
            $titleText = $dom->createTextNode($v);
            $msg->appendChild($titleText);
        }
        return $dom->saveXML();
    }
}
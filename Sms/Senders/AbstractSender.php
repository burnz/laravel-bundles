<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/12
 * Time: 07:43
 */

namespace Xjtuwangke\Sms\Senders;

use Xjtuwangke\HttpClient\HTTPApiClient\HTTPApiClient;
use Xjtuwangke\Sms\Exceptions\InvalidMobileNumberException;
use Xjtuwangke\Sms\SenderFactory;
use Xjtuwangke\HttpClient\HTTPApiClient\Middleware\BadResponseMiddleware;

abstract class AbstractSender extends HTTPApiClient{

    protected $appkey = null;

    protected $appsecret = null;

    protected $responseMiddleware = array(
        BadResponseMiddleware::class
    );

    protected function onConstruct(){
        $this->appkey = $this->config->get( 'appkey' );
        $this->appsecret = $this->config->get( 'appsecret' );
    }

    public function send( $to , $message  ){
        $result = null;
        if( false == SenderFactory::validate( $to ) ){
            throwException( new InvalidMobileNumberException("非法的手机号码:{$to}") );
        }
        else{
            $result = $this->doSend( $to , $message );
            $this->log->info("发送短信" , array(
                'to' => $to ,
                'message' => $message ,
                'result' => $result ,
            ));
        }
        return $result;
    }

    abstract protected function doSend( $to , $message );


}
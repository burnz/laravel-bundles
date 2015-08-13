<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/12
 * Time: 06:43
 */

namespace Xjtuwangke\Sms\Senders;

use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;
use Xjtuwangke\Sms\SenderContract;

class Tui3 extends AbstractSender implements SenderContract{

    protected $url = "http://www.tui3.com/api/send/";

    protected $responseMiddleware = array
    (
        Tui3ResponseMiddleware::class
    );

    protected function doSend( $to , $message ){
        $request = new ClientRequest( $this->url , ClientRequest::GET );
        $request->setParameters(array(
            'k' => $this->appkey ,
            'r' => 'json' ,
            'p' => 1 ,
            't' => $to ,
            'c'  => $message
        ));
        $response = $this->sendRequest( $request );
        return true;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/20
 * Time: 22:48
 */

namespace Xjtuwangke\Sms\Senders;


use Xjtuwangke\HttpClient\HTTPApiClient\Middleware\ResponseMiddleware;
use Xjtuwangke\HttpClient\HTTPApiClient\Middleware\ResponseMiddlewareContract;

use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;
use Xjtuwangke\HttpClient\HTTPApiClient\HTTPApiClient;
use Symfony\Component\HttpFoundation\Response;
use Xjtuwangke\Sms\Exceptions\UnknownException;

class Tui3ResponseMiddleware extends ResponseMiddleware implements ResponseMiddlewareContract{

    public function handle( HTTPApiClient $apiClient , Response $response ,  ClientRequest $clientRequest ){
        $responseArray = @ json_decode( $response->getContent() , true );
        if( isset( $responseArray['err_code'] ) ){
            if( $responseArray['err_code'] == '0' ){
                return $response;
            }
        }
        $e = new UnknownException( $response->getContent() );
        $this->throwException( $e , $response , $clientRequest );
    }
}
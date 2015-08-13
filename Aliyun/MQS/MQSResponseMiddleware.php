<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/20
 * Time: 22:09
 */

namespace Xjtuwangke\Aliyun\MQS;

use Xjtuwangke\Aliyun\MQS\Exceptions\MQSEmptyMessageException;
use Xjtuwangke\Aliyun\MQS\Exceptions\MQSResponseException;
use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;
use Xjtuwangke\HttpClient\HTTPApiClient\HTTPApiClient;
use Symfony\Component\HttpFoundation\Response;
use Xjtuwangke\HttpClient\HTTPApiClient\Middleware\ResponseMiddlewareContract;

class MQSResponseMiddleware implements ResponseMiddlewareContract{

    /**
     * @param HTTPApiClient $apiClient
     * @param Response      $response
     * @param ClientRequest $clientRequest
     * @return Response
     * @throws MQSEmptyMessageException
     * @throws MQSResponseException
     */
    public function handle( HTTPApiClient $apiClient , Response $response ,  ClientRequest $clientRequest ){
        if( (int) $response->getStatusCode() >= 400 ){
            if( (int) $response->getStatusCode() == 404 ){
                //404 有可能是 Message not exist. 或者是 The queue name you provided is not exist.
                if( strpos( $response->getContent() , 'Message not exist.') ){
                    throw new MQSEmptyMessageException;
                }
            }
            $e = new MQSResponseException("MQS返回状态码:" . $response->getStatusCode() );
            $e->setExceptionMetaData( 'content' , $response->getContent() );
            $e->setExceptionMetaData( 'header' , $response->headers );
            $e->setExceptionMetaData( 'request' , $clientRequest->toArray() );
            throw $e;
        }
        return $response;
    }
}
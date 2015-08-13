<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/20
 * Time: 22:32
 */

namespace Xjtuwangke\HttpClient\HTTPApiClient\Middleware;

use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;
use Xjtuwangke\HttpClient\HTTPApiClient\Exceptions\HTTPApiClientException;
use Xjtuwangke\HttpClient\HTTPApiClient\HTTPApiClient;
use Symfony\Component\HttpFoundation\Response;

abstract class ResponseMiddleware implements ResponseMiddlewareContract{

    /**
     * @param HTTPApiClient $apiClient
     * @param Response      $response
     * @param ClientRequest $clientRequest
     * @return mixed
     */
    abstract public function handle( HTTPApiClient $apiClient , Response $response ,  ClientRequest $clientRequest );

    /**
     * @param HTTPApiClientException $e
     * @param Response               $response
     * @param ClientRequest          $clientRequest
     * @throws HTTPApiClientException
     */
    protected function throwException( HTTPApiClientException $e , Response $response ,  ClientRequest $clientRequest ){
        $e->setExceptionMetaData( 'status_code' , $response->getStatusCode() );
        $e->setExceptionMetaData( 'content' , $response->getContent() );
        $e->setExceptionMetaData( 'header' , $response->headers );
        $e->setExceptionMetaData( 'request' , $clientRequest->toArray() );
        throw $e;
    }
}
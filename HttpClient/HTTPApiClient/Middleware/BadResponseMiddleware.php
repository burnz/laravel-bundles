<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/20
 * Time: 22:29
 */

namespace Xjtuwangke\HttpClient\HTTPApiClient\Middleware;

use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;
use Xjtuwangke\HttpClient\HTTPApiClient\Exceptions\BadResponseException;
use Xjtuwangke\HttpClient\HTTPApiClient\HTTPApiClient;
use Symfony\Component\HttpFoundation\Response;

class BadResponseMiddleware extends ResponseMiddleware implements ResponseMiddlewareContract{

    /**
     * @param HTTPApiClient $apiClient
     * @param Response      $response
     * @param ClientRequest $clientRequest
     * @return mixed
     */
    public function handle( HTTPApiClient $apiClient , Response $response ,  ClientRequest $clientRequest ){
        if( 200 != $response->getStatusCode() ){
            $e = new BadResponseException();
            $this->throwException( $e , $response , $clientRequest );
        }
    }
}
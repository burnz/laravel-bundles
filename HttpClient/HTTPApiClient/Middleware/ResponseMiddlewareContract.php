<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/20
 * Time: 21:59
 */

namespace Xjtuwangke\HttpClient\HTTPApiClient\Middleware;

use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;
use Xjtuwangke\HttpClient\HTTPApiClient\HTTPApiClient;
use Symfony\Component\HttpFoundation\Response;

interface ResponseMiddlewareContract {

    /**
     * @param HTTPApiClient $apiClient
     * @param Response      $response
     * @param ClientRequest $clientRequest
     * @return mixed
     */
    public function handle( HTTPApiClient $apiClient , Response $response ,  ClientRequest $clientRequest );

}
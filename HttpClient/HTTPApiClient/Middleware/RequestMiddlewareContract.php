<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/20
 * Time: 21:56
 */

namespace Xjtuwangke\HttpClient\HTTPApiClient\Middleware;

use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;
use Xjtuwangke\HttpClient\HTTPApiClient\HTTPApiClient;

interface RequestMiddlewareContract {

    /**
     * 签名特定的请求
     * @param HTTPApiClient $apiClient
     * @param ClientRequest $clientRequest
     * @return ClientRequest
     */
    public function handle( HTTPApiClient $apiClient , ClientRequest $clientRequest );

}
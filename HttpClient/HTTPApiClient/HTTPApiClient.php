<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/7
 * Time: 15:01
 */

namespace Xjtuwangke\HttpClient\HTTPApiClient;

use Symfony\Component\HttpFoundation\Response;
use Xjtuwangke\BugSnag\Exception;
use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;
use Xjtuwangke\HttpClient\Contract as HttpClientContract;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Logging\Log as Log;
use Xjtuwangke\HttpClient\HTTPApiClient\Middleware\RequestMiddlewareContract;
use Xjtuwangke\HttpClient\HTTPApiClient\Middleware\ResponseMiddlewareContract;


abstract class HTTPApiClient {

    /**
     * @var string
     */
    protected $version = "1.0";

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var HttpClientContract
     */
    protected $httpClient;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var Log
     */
    protected $log;

    /**
     * @var array
     */
    protected $requestMiddleware = [];

    /**
     * @var array
     */
    protected $responseMiddleware = [];

    /**
     * @param Config             $config
     * @param HttpClientContract $httpClient
     * @param Cache              $cache
     * @param Log                $log
     */
    function __construct( Config $config , HttpClientContract $httpClient , Cache $cache , Log $log ){
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->log = $log;
        $this->cache = $cache;
        $this->onConstruct();
    }

    /**
     *
     */
    protected function onConstruct(){

    }

    /**
     * @return string
     */
    public function getVersion(){
        return $this->version;
    }

    /**
     * @param ClientRequest $request
     * @return Response
     * @throws Exception
     */
    public function sendRequest( ClientRequest $request ){
        foreach( $this->requestMiddleware as $class ){
            $middleware = new $class;
            if( $middleware instanceof RequestMiddlewareContract ){
                $request = $middleware->handle( $this , $request );
            }
            else{
                throw new Exception("{$class}不满足RequestMiddlewareContract");
            }
        }
        $response = $this->httpClient->getResponse( $request );
        foreach( $this->responseMiddleware as $class ){
            $middleware = new $class;
            if( $middleware instanceof ResponseMiddlewareContract ){
                $response = $middleware->handle( $this , $response , $request );
            }
            else{
                throw new Exception("{$class}不满足ResponseMiddlewareContract");
            }
        }
        return $response;
    }
}
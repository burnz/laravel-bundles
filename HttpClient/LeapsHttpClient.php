<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/12
 * Time: 06:58
 */

namespace Xjtuwangke\HttpClient;


use Leaps\HttpClient\Adapter\Curl;
use Leaps\HttpClient\Adapter\Fsock;
use Symfony\Component\HttpFoundation\Response as Response;
use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;

/**
 * @deprecated
 * setContent无法直接设定raw_data...请勿使用
 * Class LeapsHttpClient
 * @package Xjtuwangke\HttpClient
 */
class LeapsHttpClient implements Contract{

    /**
     * @var Curl
     */
    protected $client;

    protected $response;

    protected $url;

    protected $method;

    protected $data;

    public function __construct(){
        $this->renew();
    }

    public function renew(){
        $this->method = ClientRequest::GET;
        $this->response = null;
        $this->data = array();
        $this->url = null;
        $this->client = new Curl();
        return $this;
    }

    public function setUrl( $url ){
        $this->url = $url;
    }

    public function setUserAgent( $agent ){
        $this->client->setUserAgent($agent);
        return $this;
    }

    public function setCookie( $cookie ){
        $this->client->setCookie( $cookie );
        return $this;
    }

    public function setHttpProxy( $host , $port ){
        $this->client->setHttpProxy( $host , $port );
        return $this;
    }

    public function setAuthorization( $username , $password ){
        $this->client->setAuthorization($username,$password);
        return $this;
    }

    public function setReferer( $referer ){
        $this->client->setReferer($referer);
        return $this;
    }

    public function setTimeout( $time ){
        $this->client->setOption(CURLOPT_TIMEOUT , $time );
        return $this;
    }

    public function setMethod( $method ){
        $this->method = $method;
        return $this;
    }

    public function setHeader( $key , $val ){
        $this->client->setHeader( $key , $val );
        return $this;
    }

    public function setHeaders( $headers ){
        foreach( $headers as $key => $value ){
            $this->client->setHeader( $key , $value );
        }
        return $this;
    }

    public function setData( $data ){
        $this->data = $data;
        return $this;
    }

    public function addFile( $field , $path , $mime ){
        $this->client->addFile( $field , $path , $mime );
        return $this;
    }

    /**
     * @param        $field
     * @param        $file
     * @param string $mime
     * @return void
     */
    public function setFile( $field , $file  , $mime = '' ){
        $this->client->addFile(  $field , $file , $mime );
    }

    /**
     * @param ClientRequest $clientRequest
     * @return Response
     */
    public function getResponse( ClientRequest $clientRequest = null ){
        if( ! is_null( $clientRequest ) ){
            $this->prepare( $clientRequest );
        }
        if( is_null( $this->response ) || ! $this->response instanceof Response ){
            switch( $this->method ){
                case ClientRequest::POST:
                    $response = $this->client->post( $this->url , $this->data );
                    break;
                case ClientRequest::DELETE:
                    $response = $this->client->delete( $this->url );
                    break;
                case ClientRequest::PUT:
                    $response = $this->client->put( $this->url , $this->data );
                    break;
                case ClientRequest::GET:
                default:
                    $response = $this->client->get( $this->url );
                    break;
            }
            $this->response = new Response( $response->getContent()?:"" , $response->getStatusCode() , $response->getHeaders() );
        }
        return $this->response;
    }

    public function prepare( ClientRequest $clientRequest ){
        $this->renew();
        $this->setUrl( $clientRequest->getUrl() );
        $this->setData( $clientRequest->getContent() );
        $this->setHeaders( $clientRequest->getHeaders() );
        $this->setMethod( $clientRequest->getVerb() );
        return $this;
    }
}
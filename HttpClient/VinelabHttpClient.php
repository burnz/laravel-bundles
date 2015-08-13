<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/19
 * Time: 16:56
 */

namespace Xjtuwangke\HttpClient;

use Symfony\Component\HttpFoundation\Response;
use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;
use Vinelab\Http\Client as HttpClient;

class VinelabHttpClient implements Contract{

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @param ClientRequest $clientRequest
     * @return Response
     */
    public function getResponse( ClientRequest $clientRequest ){
        $client = new HttpClient();
        $headers = array();
        foreach( $clientRequest->getHeaders() as $key => $val ){
            $headers[] = "$key: $val";
        }
        switch( $clientRequest->getVerb() ){
            case ClientRequest::POST:
                $response = $client->post(array(
                    'url' => $clientRequest->getUrl() ,
                    'content' => $clientRequest->getContent() ,
                    'headers' => $headers ,
                ));
                break;
            case ClientRequest::DELETE:
                $response = $client->delete(array(
                    'url' => $clientRequest->getUrl() ,
                    //'content' => $clientRequest->getContent() ,
                    'headers' => $headers ,
                ));
                break;
            case ClientRequest::PUT:
                $response = $client->put(array(
                    'url' => $clientRequest->getUrl() ,
                    'content' => $clientRequest->getContent() ,
                    'headers' => $headers ,
                ));
                break;
            case ClientRequest::GET:
            default:
                $response = $client->get(array(
                    'url' => $clientRequest->getUrl() ,
                    'params' => $clientRequest->getParameters() ,
                    'headers' => $headers ,
                ));
                break;
        }
        if( $response instanceof \Vinelab\Http\Response ){
            $content = $response->content();
            if( is_bool( $content ) || is_null( $content )  ){
                $content = '';
            }
            $responseBag = new Response( $content , $response->statusCode() , $response->headers() );
            return $responseBag;
        }
        else{
            return null;
        }
    }
}
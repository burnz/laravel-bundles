<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/20
 * Time: 22:07
 */

namespace Xjtuwangke\Aliyun\MQS;


use Xjtuwangke\HttpClient\HTTPApiClient\Middleware\RequestMiddlewareContract;
use Xjtuwangke\HttpClient\HTTPApiClient\HTTPApiClient;
use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;

class MQSRequestMiddleware implements RequestMiddlewareContract{

    const X_MQS_VERSION =  '2014-07-08' ;

    const CONTENT_TYPE = 'text/xml;utf-8' ;

    public function handle( HTTPApiClient $apiClient , ClientRequest $clientRequest ){
        if( ! $apiClient instanceof Client ){
            return $clientRequest;
        }
        $content_md5 = base64_encode( md5( $clientRequest->getContent() ));
        $gmt_date = Util::getGMTDate();
        $clientRequest->setHeader( 'x-mqs-version' , static::X_MQS_VERSION );

        $canonicalizedMQSHeaders = array();

        foreach( $clientRequest->getHeaders() as $key => $value ){
            if( preg_match( '/^x\-mqs\-/' , $key ) ){
                $canonicalizedMQSHeaders[ $key ] = $value;
            }
        }
        ksort( $canonicalizedMQSHeaders , SORT_STRING );

        $sign = Util::getSignature(
            $clientRequest->getVerb() ,
            $content_md5 ,
            static::CONTENT_TYPE ,
            $gmt_date ,
            $canonicalizedMQSHeaders,
            $clientRequest->getUrl() ,
            $apiClient->getAccessKey() ,
            $apiClient->getAccessSecret()
        );

        $clientRequest->setHeader( 'Host' ,  $apiClient->getQueueOwnerId() . "." . $apiClient->getMqsUrl() );
        $clientRequest->setHeader( 'Date' , $gmt_date );
        $clientRequest->setHeader( 'Content-Type' , static::CONTENT_TYPE );
        $clientRequest->setHeader( 'Content-MD5' , $content_md5 );
        $clientRequest->setHeader( 'Authorization' , $sign );
        $clientRequest->setUrl( 'http://' . $apiClient->getQueueOwnerId() . "." . $apiClient->getMqsUrl() . $clientRequest->getUrl() );
        return $clientRequest;
    }
}
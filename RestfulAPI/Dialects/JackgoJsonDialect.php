<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/21
 * Time: 15:05
 */

namespace Xjtuwangke\RestfulAPI\Dialects;

use Response;
use Xjtuwangke\RestfulAPI\Errors\APIError;
use Xjtuwangke\RestfulAPI\Http\APIResponse;

/**
 * Class JackgoJsonDialect
 * @package Xjtuwangke\RestfulAPI\Dialects
 */
class JackgoJsonDialect implements DialectContract
{

    /**
     * @var array
     */
    protected $headers = array();

    /**
     * @param array $headers
     * @return void
     */
    public function setHeaders( array $headers ){
        $this->headers = $headers;
    }

    /**
     * @param APIResponse $apiResponse
     * @return \Illuminate\Http\JsonResponse
     */
    public function translate( APIResponse  $apiResponse ){
        $output = array(
            'errors' => array() ,
            'debug'  => $apiResponse->getDebug() ,
            'results' => $apiResponse->getResults() ,
            'flag' => 0 ,
        );
        $code = 200;
        if( $apiResponse->isOK() ){
            //没有错误
            $output['flag'] = 1;
        }
        else{
            //有错误
            $output['flag'] = 0;
            foreach( $apiResponse->getErrors() as $error ){
                if( $error instanceof APIError ){
                    $output['errors'][$error->getCode()] = $error->getMessage();
                    $code = $error->statusCode();
                }
                elseif( $error instanceof \Exception ){
                    $output['errors'][$error->getCode()] = $error->getMessage();
                    $code = 422;
                }
            }
        }
        return Response::json( $output , $code , $this->headers , JSON_UNESCAPED_UNICODE );
    }
}
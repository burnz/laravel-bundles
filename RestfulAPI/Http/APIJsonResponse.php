<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/11
 * Time: 01:48
 */

namespace Xjtuwangke\RestfulAPI\Http;

use Illuminate\Http\JsonResponse;
use Xjtuwangke\RestfulAPI\Errors\APIError;

class APIJsonResponse extends JsonResponse{

    protected $responseData = array();

    protected $is_dirty = false;

    /**
     * Constructor.
     *
     * @param  mixed  $data
     * @param  array  $headers
     */
    public function __construct($data = array() , $headers = array() ){
        $this->responseData = array(
            'flag'   => 1 ,
            'results' => $data ,
            'errors' => array() ,
            'debug'  => array() ,
        );
        parent::__construct($this->responseData, 200, $headers , JSON_UNESCAPED_UNICODE );
    }

    /**
     * @param APIError $error
     */
    public function pushError( APIError $error ){
        $this->responseData['errors'][$error->getCode()] = $error->getMessage();
        $this->pushDebug( $error->getErrorContext() );
        $this->responseData['flag'] = 0;
        $this->setStatusCode( $error->statusCode() );
        $this->is_dirty = true;
    }

    /**
     * @param \Exception $error
     */
    public function pushException( \Exception $error ){
        $this->responseData['errors'][$error->getCode()] = $error->getMessage();
        $this->responseData['flag'] = 0;
        $this->setStatusCode( 422 );
        $this->is_dirty = true;
    }

    /**
     * @param $debug
     */
    public function pushDebug( $debug ){
        $this->responseData['debug'][] = $debug;
        $this->is_dirty = true;
    }

    /**
     * @param $results
     */
    public function setResults( $results ){
        $this->responseData['results'] = $results;
        $this->is_dirty = true;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendContent(){
        if( $this->is_dirty ){
            $this->setData( $this->responseData );
            $this->is_dirty = false;
        }
        return parent::sendContent();
    }
}
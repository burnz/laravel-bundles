<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/21
 * Time: 14:55
 */

namespace Xjtuwangke\RestfulAPI\Http;

use Xjtuwangke\RestfulAPI\Dialects\DialectContract;
use Xjtuwangke\RestfulAPI\Dialects\JackgoJsonDialect;
use Xjtuwangke\RestfulAPI\Errors\APIError;

class APIResponse
{
    /**
     * @var array
     */
    protected $message = array(
        'errors' => array() ,
        'debug'  => array() ,
        'results' => array() ,
    );

    /**
     * @var DialectContract
     */
    protected $dialect;

    /**
     * @var null |  \Exception
     */
    protected $last_error = null;

    /**
     * @param DialectContract $dialect
     */
    public function setDialect( DialectContract $dialect ){
        $this->dialect = $dialect;
    }

    /**
     * @return DialectContract
     */
    public function getDialect(){
        if( is_null( $this->dialect ) ){
            $this->dialect = new JackgoJsonDialect();
        }
        return $this->dialect;
    }

    /**
     * @param APIError $error
     */
    public function pushError( APIError $error ){
        $this->message['errors'][] = $error;
        $this->pushDebug( $error->getErrorContext() );
        $this->last_error = $error;
    }

    /**
     * @param \Exception $error
     */
    public function pushException( \Exception $error ){
        $this->message['errors'][$error->getCode()] = $error->getMessage();
        $this->last_error = $error;
    }

    /**
     * @param $debug
     */
    public function pushDebug( $debug ){
        $this->message['debug'][] = $debug;
    }

    /**
     * @param $results
     */
    public function setResults( $results ){
        $this->message['results'] = $results;
    }

    /**
     * @return mixed
     */
    public function getErrors(){
        return $this->message['errors'];
    }

    /**
     * @return null | \Exception
     */
    public function getLastError(){
        return $this->last_error;
    }

    /**
     * @return bool
     */
    public function isOK(){
        return is_null( $this->last_error );
    }

    /**
     * @return mixed
     */
    public function getDebug(){
        return $this->message['debug'];
    }

    /**
     * @return mixed
     */
    public function getResults(){
        return $this->message['results'];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse(){
        return $this->getDialect()->translate( $this );
    }
}
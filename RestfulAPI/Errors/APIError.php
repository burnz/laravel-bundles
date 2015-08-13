<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/26
 * Time: 01:59
 */

namespace Xjtuwangke\RestfulAPI\Errors;

use Xjtuwangke\BugSnag\Exception;

abstract class APIError extends Exception{

    /**
     * context
     * @var array
     */
    protected $context = array();

    public function getErrorContext(){
        return $this->context;
    }

    public function setErrorContext( array $context ){
        $this->context = $context;
        $this->setExceptionMetaData( 'api-context' , $context );
    }

    public function statusCode(){
        return 422;
    }

}
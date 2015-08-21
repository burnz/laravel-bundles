<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/11
 * Time: 01:48
 */

namespace Xjtuwangke\RestfulAPI\Http;

use Xjtuwangke\RestfulAPI\Dialects\JackgoJsonDialect;

class APIJsonResponse extends APIResponse{

    /**
     * Constructor.
     *
     * @param  mixed  $data
     * @param  array  $headers
     */
    public function __construct($data = array() , $headers = array() ){
        $this->message['results'] = $data;
        $this->setDialect( new JackgoJsonDialect() );
        $this->getDialect()->setHeaders( $headers );
    }
}
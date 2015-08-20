<?php
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */

namespace Xjtuwangke\Aliyun\SLS\Models;

/**
 * The response of the ListLogstores API from sls.
 *
 * @author sls_dev
 */
class ListLogstoresResponse extends Response {
    
    /**
     * @var integer the number of total logstores from the response
     */
    private $count;
    
    /**
     * @var array all logstore
     */
    private $logstores;
    
    /**
     * ListLogstoresResponse constructor
     *
     * @param array $resp
     *            ListLogstores HTTP response body
     * @param array $header
     *            ListLogstores HTTP response header
     */
    public function __construct($resp, $header) {
        parent::__construct ( $header );
        $this->count = $resp ['count'];
        $this->logstores = $resp ['logstores'];
    }
    
    /**
     * Get total count of logstores from the response
     *
     * @return integer the number of total logstores from the response
     */
    public function getCount() {
        return $this->count;
    }
    
    /**
     * Get all the logstores from the response
     *
     * @return array all logstore
     */
    public function getLogstores() {
        return $this->logstores;
    }
}

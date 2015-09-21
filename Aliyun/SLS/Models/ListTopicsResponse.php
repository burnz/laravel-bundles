<?php

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */

namespace Xjtuwangke\Aliyun\SLS\Models;

/**
 * The response of the ListTopics API from sls.
 *
 * @author sls_dev
 */
class ListTopicsResponse extends Response {

    /**
     * @var integer the number of all the topics from the response
     */
    private $count;

    /**
     * @var array topics list
     */
    private $topics;

    /**
     * @var string/null the next token from the response. If there is no more topic to list, it will return None
     */
    private $nextToken;
    
    /**
     * ListTopicsResponse constructor
     *
     * @param array $resp
     *            ListTopics HTTP response body
     * @param array $header
     *            ListTopics HTTP response header
     */
    public function __construct($resp, $header) {
        parent::__construct ( $header );
        $this->count = $resp ['count'];
        $this->topics = $resp ['topics'];
        $this->nextToken = isset ( $resp ['next_token'] ) ? $resp ['next_token'] : NULL;
    }
    
    /**
     * Get the number of all the topics from the response
     *
     * @return integer the number of all the topics from the response
     */
    public function getCount() {
        return $this->count;
    }
    
    /**
     * Get all the topics from the response
     *
     * @return array topics list
     */
    public function getTopics() {
        return $this->topics;
    }
    
    /**
     * Return the next token from the response. If there is no more topic to list, it will return None
     *
     * @return string/null next token used to list more topics
     */
    public function getNextToken() {
        return $this->nextToken;
    }
}

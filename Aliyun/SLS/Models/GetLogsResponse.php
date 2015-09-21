<?php
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */

namespace Xjtuwangke\Aliyun\SLS\Models;

/**
 * The response of the GetLog API from sls.
 *
 * @author sls_dev
 */
class GetLogsResponse extends Response {
    
    /**
     * @var integer log number
     */
    private $count;

    /**
     * @var string logs query status(Complete or InComplete)
     */
    private $progress;

    /**
     * @var array QueriedLog array, all log data
     */
    private $logs;
    
    /**
     * GetLogsResponse constructor
     *
     * @param array $resp
     *            GetLogs HTTP response body
     * @param array $header
     *            GetLogs HTTP response header
     */
    public function __construct($resp, $header) {
        parent::__construct ( $header );
        $this->count = $resp ['count'];
        $this->progress = $resp ['progress'];
        $this->logs = array ();
        foreach ( $resp ['logs'] as $data ) {
            $contents = $data;
            $time = $data ['__time__'];
            $source = $data ['__source__'];
            unset ( $contents ['__time__'] );
            unset ( $contents ['__source__'] );
            $this->logs [] = new QueriedLog ( $time, $source, $contents );
        }
    }
    
    /**
     * Get log number from the response
     *
     * @return integer log number
     */
    public function getCount() {
        return $this->count;
    }
    
    /**
     * Check if the get logs query is completed
     *
     * @return bool true if this logs query is completed
     */
    public function isCompleted() {
        return $this->progress == 'Complete';
    }
    
    /**
     * Get all logs from the response
     *
     * @return array QueriedLog array, all log data
     */
    public function getLogs() {
        return $this->logs;
    }
}

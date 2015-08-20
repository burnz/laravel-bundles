<?php
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */

namespace Xjtuwangke\Aliyun\SLS\Models;

/**
 * The response of the PutLogs API from sls.
 *
 * @author sls_dev
 */
class PutLogsResponse extends Response {
    /**
     * PutLogsResponse constructor
     *
     * @param array $header
     *            PutLogs HTTP response header
     */
    public function __construct($headers) {
        parent::__construct ( $headers );
    }
}

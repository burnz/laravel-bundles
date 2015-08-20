<?php
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */

namespace Xjtuwangke\Aliyun\SLS\Models;

/**
 * The request used to list logstore from sls.
 *
 * @author sls_dev
 */
class ListLogstoresRequest extends Request{
    
    /**
     * ListLogstoresRequest constructor
     * 
     * @param string $project project name
     */
    public function __construct($project=null) {
        parent::__construct($project);
    }
}

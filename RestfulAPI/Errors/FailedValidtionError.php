<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/11
 * Time: 01:58
 */

namespace Xjtuwangke\RestfulAPI\Errors;

class FailedValidtionError extends APIError {

    protected $message = "参数验证失败";

    protected $code = "001";

}
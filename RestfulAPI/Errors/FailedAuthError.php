<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/11
 * Time: 02:19
 */

namespace Xjtuwangke\RestfulAPI\Errors;


class FailedAuthError extends APIError{

    protected $message = "用户认证失败";

    protected $code = "002";

}
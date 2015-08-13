<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/19
 * Time: 15:02
 */

namespace Xjtuwangke\Aliyun\MQS\Exceptions;


use Xjtuwangke\BugSnag\DontReportExceptionContract;

class MQSEmptyMessageException extends MQSResponseException implements DontReportExceptionContract{

}
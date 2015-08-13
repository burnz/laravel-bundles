<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 03:26
 */

namespace Xjtuwangke\LaravelModels\Exceptions;


use Xjtuwangke\BugSnag\DontReportExceptionContract;
use Xjtuwangke\BugSnag\Exception;

class UserHasBeenBannedException extends Exception implements DontReportExceptionContract{

}
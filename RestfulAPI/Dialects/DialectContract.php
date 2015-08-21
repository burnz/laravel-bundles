<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/21
 * Time: 15:03
 */

namespace Xjtuwangke\RestfulAPI\Dialects;


use Xjtuwangke\RestfulAPI\Http\APIResponse;
use Symfony\Component\HttpFoundation\Response;

interface DialectContract
{
    /**
     * @param APIResponse $apiResponse
     * @return Response
     */
    public function translate( APIResponse  $apiResponse );

    /**
     * @param array $headers
     * @return mixed
     */
    public function setHeaders( array $headers );
}
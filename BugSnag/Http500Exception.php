<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/10/12
 * Time: 11:57
 */

namespace Xjtuwangke\BugSnag;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class Http500Exception extends Exception
{
    /**
     * @return Response | null
     */
    public function getHtmlResponse(){
        return response()->view("errors.500", ['exception' => $this], 500);
    }

    /**
     *  @return JsonResponse | null
     */
    public function getJsonResponse(){
        return new JsonResponse( $this->toArray() ,500,array(),JSON_UNESCAPED_UNICODE);
    }
}
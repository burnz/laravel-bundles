<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/13
 * Time: 19:29
 */

namespace Xjtuwangke\Http\Middleware;


use Illuminate\Http\Request;
use Closure;

/**
 * HttpOnly方式访问
 * Class HttpsOnlyMiddleware
 * @package Xjtuwangke\Http\Middleware
 */
class HttpsOnlyMiddleware {

    /**
     * uri array
     * @var array
     */
    protected $except = array();

    /**
     * 强制https redirect的环境
     * @var array
     */
    protected $environments = array();

    public function handle( Request $request , Closure $next ){
        if( ! in_array( app()->environment() , $this->environments ) ){
            return $next( $request );
        }
        if( $request->isSecure() ){
            return $next( $request );
        }
        if( ! is_array( $this->except ) ){
            return $next( $request );
        }
        if( $this->shouldPassThrough( $request ) ){
            return $next( $request );
        }
        return redirect()->secure( $request->getRequestUri() );
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldPassThrough($request){
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return true;
            }
        }
        return false;
    }
}
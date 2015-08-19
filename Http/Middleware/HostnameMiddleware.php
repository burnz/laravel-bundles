<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/18
 * Time: 11:49
 */

namespace Xjtuwangke\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Str;

class HostnameMiddleware
{

    /**
     * host redirect array
     * @var array
     */
    protected $hosts = array(
        //'www.somedomain.com' => 'domain.com' ,
        //'*' => 'g.cn' ,
    );

    /**
     * 允许的hosts *为阻挡全部
     * @var array
     */
    protected $allows = array(
        '*' ,
        // 'valid.com' ,
    );

    /**
     * @var Request
     */
    protected $request;

    /**
     * 需要做redirect的环境 存在'*'时即为全部环境
     * @var array
     */
    protected $environments = array('*');

    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle( Request $request , Closure $next ){
        $this->request = $request;
        if( ! in_array( '*' , $this->environments ) && ! in_array( app()->environment() , $this->environments ) ){
            return $next( $request );
        }
        $host = strtolower( $request->getHost() );
        foreach( $this->hosts as $pattern => $targetHost ){
            if( $this->hostMatches( $pattern , $host ) ){
                $host = $targetHost;
                if( $this->isAllowedHost( $targetHost ) ){
                    if( $queryString = $request->getQueryString() ){
                        $queryString = '?' . $queryString;
                    }
                    $url = $request->getScheme() . '://' . $targetHost . $request->getPathInfo() . $queryString;
                    return \Response::make('',301,array('Location'=>$url));
                }
                else{
                    return $this->deniedResponse( $host );
                }
            }
        }
        if( $this->isAllowedHost( $host ) ){
            return $next( $request );
        }
        else{
            return $this->deniedResponse( $host );
        }
    }

    /**
     * 是否是合法的host
     * @param $host
     * @return bool
     */
    protected function isAllowedHost( $host ){
        foreach( $this->allows as $pattern ){
            if( $this->hostMatches( $pattern , $host ) ){
                return true;
            }
        }
        return false;
    }

    /**
     * 恶意解析的响应
     * @param $host
     * @return \Illuminate\Http\Response
     */
    protected function deniedResponse( $host ){
        return \Response::make('',301,array('Location'=>'https://baidu.com'));
    }

    /**
     * @param $pattern
     * @param $host
     * @return bool|int
     */
    protected function hostMatches( $pattern , $host ){
        if( '*' === $pattern ){
            return true;
        }
        if( $host === $pattern ){
            return true;
        }
        return Str::is( $pattern , $host );
    }
}
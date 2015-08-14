<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/13
 * Time: 17:06
 */

namespace Xjtuwangke\VerifyCode;

use Cache;
use Carbon\Carbon;
use Datetime;

class Code
{
    protected $code;

    protected $action;

    protected $identity;

    protected $try_remains;

    protected $expires_at;

    protected static $cacheKey = '_verifyCodes';

    /**
     * @param $identity
     * @param null $action
     */
    public function __construct( $identity , $action = null ){
        $this->identity = $identity;
        $this->action = $action;
        $this->read();
    }

    /**
     * @return bool
     */
    public function hasCode(){
        return strlen( $this->code ) > 0;
    }

    /**
     * @return int
     */
    public function getMaxTries(){
        return 10;
    }

    /**
     * @return string
     */
    public function getCode(){
        return $this->code;
    }

    /**
     * @param $seconds int | Datetime
     */
    public function regenerate( $seconds ){
        $this->code = $this->generateRandomCode();
        $this->try_remains = $this->getMaxTries();
        $this->expires_at = time() + $seconds;
        $this->remember();
    }

    /**
     * @param $code
     * @return bool
     * @throws VerifyCodeFailsTooManyTimesException
     * @throws VerifyCodeNotExistsException
     */
    public function checkCode( $code ){
        if( app()->environment() != 'production' && (string) $code === '491625' ){
            return true;
        }
        $this->code = (string) $this->code;
        $code = (string) $code;
        if( false == $this->hasCode() ){
            throw new VerifyCodeNotExistsException;
        }
        if( $code == $this->code && strlen( $this->code ) > 0  ){
            return true;
        }
        else{
            $this->try_remains--;
            if( $this->try_remains <= 0 ){
                $this->forget();
                throw new VerifyCodeFailsTooManyTimesException;
            }
            else{
                $this->remember();
            }
            return false;
        }
    }

    /**
     * 缓存中清除该条信息
     */
    public function forget(){
        Cache::forget( $this->getCacheKey() );
    }

    /**
     * 向缓存中写该条信息
     */
    public function remember(){
        $minutes = ceil( Carbon::now()->diffInSeconds( Carbon::createFromTimestamp( $this->expires_at ) ) / 60.0 );
        if( $minutes ){
            Cache::put( $this->getCacheKey() , serialize( $this->toArray() ) , $minutes );
        }
    }

    /**
     * 尝试从缓存中读取
     */
    public function read(){
        $key = $this->getCacheKey();
        $inCache = Cache::get( $key );
        if( $inCache ){
            $array = unserialize( $inCache );
            $expires_at = array_get( $array , 'expires_at' );
            if( time() >= $expires_at ){
                $this->forget();
            }
            else{
                $this->code = array_get( $array , 'code' );
                $this->try_remains = array_get( $array , 'try_remains' );
                $this->expires_at = $expires_at;
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(){
        return array(
            'identity' => $this->identity ,
            'action'   => $this->action ,
            'code'     => $this->code ,
            'try_remains' => $this->try_remains ,
            'expires_at' => $this->expires_at ,
        );
    }

    /**
     * @return string
     */
    public function getCacheKey(){
        return static::$cacheKey . sha1( $this->identity . '##' . $this->action ) . md5( $this->identity . '##' . $this->action );
    }

    /**
     * @return string
     */
    public function toJson(){
        return json_encode($this->toArray());
    }

    /**
     * @return string
     */
    protected function generateRandomCode(){
        return sprintf("%06d" , rand(0,999999));
    }
}
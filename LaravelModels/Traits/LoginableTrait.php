<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 02:14
 */

namespace Xjtuwangke\LaravelModels\Traits;


use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Schema\Blueprint;
use Request;
use Xjtuwangke\LaravelModels\Exceptions\UserHasBeenBannedException;
use Illuminate\Contracts\Auth\Authenticatable as LoginContract;

trait LoginableTrait {

    use Authenticatable;

    /**
     * @param Blueprint $table
     * @return Blueprint
     */
    public static function _schema_LoginableTrait( Blueprint $table ){
        $table->string( 'password' )->nullable();
        $table->rememberToken( 'remember_token' );
        $table->dateTime( 'last_login' )->nullable();
        $table->string( 'last_ip' )->nullable();
        $table->integer( 'fails' )->default( 0 );
        $table->enum( 'is_banned' , [ 0 , 1 ] )->default( 0 );
        $table->text( 'ban_reason' )->nullable();
        $table->enum( 'locked_screen' , [ 0 , 1 ] )->default( 0 );
        return $table;
    }

    /**
     * 尝试登陆
     * @param array $credentials
     * @param bool  $remember
     * @param bool  $login
     * @return bool
     * @throws UserHasBeenBannedException
     */
    public static function attempt( array $credentials , $remember = false , $login = true ){
        $credentials[ 'is_banned' ] = 0;
        $user = null;
        if( $login ){
            $userAttributes = array_except( $credentials , [ 'password' , 'banned'] );
            if( ! empty( $userAttributes ) ){
                $user = static::where( $userAttributes )->first();
            }
        }
        $guard = static::getAuthDriver();
        if( is_null( $guard ) ){
            return false;
        }
        else{
            $result = $guard->attempt( $credentials , $remember , $login );
            if( false == $result ){
                if( $user ){
                    if( false == $user->isBanned() ){
                        $user->fails = $user->fails + 1;
                        $user->save();
                    }
                    else{
                        throw new UserHasBeenBannedException( $user->ban_reason );
                    }
                }
            }
            else{
                $user = static::getAuthDriver()->getUser();
                $user->last_login = Carbon::now();
                $user->last_ip    = Request::ip();
                $user->fails      = 0;
                $user->save();
                return true;
            }
        }
    }

    /**
     * @param Authenticatable $user
     * @param bool|false $remember
     * @return bool
     */
    public static function login( LoginContract $user , $remember = false ){
        $guard = static::getAuthDriver();
        if( is_null( $guard ) ){
            return false;
        }
        else{
            $guard->login( $user , $remember );
            $user->last_login = Carbon::now();
            $user->last_ip    = Request::ip();
            $user->fails      = 0;
            $user->save();
        }
        return true;
    }

    /**
     * 用户是否锁屏
     * @return bool
     */
    public function isLockedScreen(){
        return 1 == $this->locked_screen;
    }

    /**
     * 锁屏
     * @param bool $lock
     */
    public function lockscreen( $lock = true ){
        $this->locked_screen = $lock?1:0;
        $this->save();
    }

    /**
     * 用户是否被禁用
     * @return bool
     */
    public function isBanned(){
        return 1 == $this->is_banned;
    }

    /**
     * 禁用用户
     * @param bool $banned
     * @param null $reason
     */
    public function setBanned( $banned = true , $reason = null ){
        if( $banned ){
            $this->is_banned = 1;
            $this->ban_reason = $reason;
        }
        else{
            $this->is_banned = 0;
            $this->ban_reason = null;
        }
        $this->save();
    }

    /**
     * 得到适用的auth driver
     * @return \Illuminate\Auth\Guard | null
     */
    public static function getAuthDriver(){
        return \Auth::driver();
    }


}
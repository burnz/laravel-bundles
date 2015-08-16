<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/16
 * Time: 14:02
 */

namespace Xjtuwangke\LaravelModels\ResetPassword;


use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Xjtuwangke\LaravelModels\BaseModel;
use Xjtuwangke\LaravelModels\UserModelBase;
use Xjtuwangke\Utils\Random;

class ResetPasswordTokens extends BaseModel
{

    /**
     * @var string
     */
    protected $table = 'password_reset_tokens';

    /**
     * @param Blueprint $table
     * @return Blueprint
     */
    public static function _schema_ResetPasswordTokens( Blueprint $table ){
        $table->string( 'key' )->index();
        $table->string( 'token' );
        $table->integer( 'user_id' )->index();
        $table->dateTime( 'expires_at' );
        return $table;
    }

    /**
     * @param UserModelBase $user
     * @param $key
     * @param int $hours
     * @return static
     */
    public static function createUserResetToken( UserModelBase $user , $key , $hours = 24 ){
        \DB::beginTransaction();
        $expires_at = Carbon::now()->addHour( $hours )->format('Y-m-d H:i:s');
        $random = Random::getRandStr('32','a-z0-9');
        $token = static::create(array(
            'token' => $random ,
            'key' => $key ,
            'user_id' => $user->getKey() ,
            'expires_at' => $expires_at ,
        ));
        \DB::commit();
        return $token;
    }

    /**
     * @return string
     */
    public function getToken(){
        return $this->token;
    }

    /**
     * @param $key
     * @param $token
     * @return ResetPasswordTokens
     * @throws InvalidTokenException
     */
    public static function valid( $key , $token ){
        $token = static::where( 'key' , $key )->where( 'token' , $token )->where('expires_at','>=',Carbon::now()->format('Y-m-d H:i:s') )->first();
        if( $token instanceof static ){
            return $token;
        }
        else{
            throw new InvalidTokenException;
        }
    }
}
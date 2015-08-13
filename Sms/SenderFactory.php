<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/12
 * Time: 06:18
 */

namespace Xjtuwangke\Sms;


use Xjtuwangke\HttpClient\Contract as HttpClient;
use Illuminate\Contracts\Logging\Log as Log;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Cache\Repository as Cache;

class SenderFactory {

    const SP_ALL = '/^1[3-9]{1}\d{9}$/';
    const SP_CMCC = '/^1(([3][456789])|([5][012789])|([8][278]))[0-9]{8}$/';
    const SP_Unicom = '/^1(([3][012])|([5][56])|([8][56]))[0-9]{8}$/';
    const SP_Telecom = '/^1(([3][3])|([5][3])|([8][09]))[0-9]{8}$/';

    protected static $senders = array(
        'tui3' => Senders\Tui3::class ,
    );

    public static function validate( $mobile ){
        if( preg_match( static::SP_ALL , $mobile ) ){
            return true;
        }
        else{
            return false;
        }
    }

    public static function make( $sender , array $config , HttpClient $client , Cache $cache , Log $logger ){
        if( array_key_exists( $sender , static::$senders ) ){
            $sender = static::$senders[$sender];
            $config = new Repository($config);
            return new $sender( $config , $client , $cache , $logger );
        }
        else{
            throw new \Exception("找不到别名{$sender}对应的类");
        }
    }


}
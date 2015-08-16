<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/16
 * Time: 14:09
 */

namespace Xjtuwangke\Utils;

use RandomLib\Factory;
use SecurityLib\Strength;

class Random {

    /**
     * 随机强度
     * @var int
     */
    protected static $strength = Strength::LOW;

    /**
     * generator
     * @var null|$generator
     */
    protected static $generator = null;

    /**
     * 最大尝试次数
     * @var int
     */
    protected static $max_try = 32;

    /**
     * 生成并获取static::$generator
     * @return null
     */
    public static function generator(){
        if( ! is_null( static::$generator ) ){
            return static::$generator;
        }
        else{
            $factory = new Factory();
            static::$generator = $factory->getGenerator( new Strength( static::$strength ) );
            return static::$generator;
        }
    }

    /**
     * @param $strength
     * @return null
     */
    public static function setStrength( $strength ){
        $factory = new Factory();
        static::$generator = $factory->getGenerator( new Strength( $strength ) );
        return static::generator();
    }

    /**
     * 生成随机整数
     * @param int   $min
     * @param int   $max
     * @param array $exclude
     * @return null
     */
    public static function int( $min = 0 , $max = PHP_INT_MAX , $exclude = array() ){
        $generator = static::generator();
        return static::attempt( function()use( $min , $max , $generator){ return $generator->generateInt( $min , $max ); } , $exclude );
    }

    /**
     * 生成随机字符串
     * @param        $length
     * @param string $charlist
     * @param array  $exclude
     * @return null
     */
    public static function string( $length , $charlist = '' , $exclude = array() ){

        $charlist = str_replace( 'a-z' , 'abcdefghijklmnopqrstuvwxyz' , $charlist );
        $charlist = str_replace( 'A-Z' , 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' , $charlist );
        $charlist = str_replace( '0-9' , '0123456789' , $charlist );

        $generator = static::generator();
        return static::attempt( function()use( $length , $charlist , $generator){ return $generator->generateString( $length , $charlist ); } , $exclude );
    }

    /**
     * 尝试
     * @param callable $func
     * @param array    $exclude
     * @return null
     */
    public static function attempt( callable $func , $exclude = array() ){
        $found = false;
        $count = 0;
        $try = null;
        while( false == $found ){
            $try = $func();
            $found = true;
            if( in_array( $try , $exclude ) ){
                $found = false;
            }
            $count++;
            if( $count >= static::$max_try ){
                return null;
            }
        }
        return $try;
    }

    /**
     * static::string的别名
     * @param int    $length
     * @param string $charlist
     * @return null
     */
    static function getRandStr( $length = 32 , $charlist = 'a-zA-Z0-9'){
        return static::string( $length , $charlist );
    }

}
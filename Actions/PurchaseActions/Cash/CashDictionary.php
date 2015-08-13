<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/26
 * Time: 02:49
 */

namespace Xjtuwangke\Actions\PurchaseActions\Cash;


class CashDictionary {

    /**
     * @var array ['RMB'=>'RMBCash']
     */
    static $types;

    static $default = RMBCash::class;

    static $supports = array(
        RMBCash::class ,
    );

    /**
     * @return array
     */
    public static function getTypes(){
        if( is_null( static::$types ) ){
            $types = array();
            foreach( static::$supports as $class ){
                $types[ $class::getType() ] = $class;
            }
            static::$types = $types;
        }
        return static::$types;
    }

}
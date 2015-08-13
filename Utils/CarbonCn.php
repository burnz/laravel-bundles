<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/23
 * Time: 08:26
 */

namespace Xjtuwangke\Utils;

use Carbon\Carbon;

/**
 * Class CarbonCn
 * @package Xjtuwangke\Utils
 * @property-read string $weekday_name
 */
class CarbonCn extends Carbon{

    /**
     * Names of days of the week.
     *
     * @var array
     */
    protected static $days = array(
        self::SUNDAY => '星期日',
        self::MONDAY => '星期一',
        self::TUESDAY => '星期二',
        self::WEDNESDAY => '星期三',
        self::THURSDAY => '星期四',
        self::FRIDAY => '星期五',
        self::SATURDAY => '星期六',
    );

    public function __get( $name ){
        if( 'weekday_name' ){
            return static::$days[ parent::__get( 'dayOfWeek') ];
        }
        else{
            return parent::__get( $name );
        }
    }
}
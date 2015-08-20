<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/20
 * Time: 14:45
 */

namespace Xjtuwangke\Aliyun\SLS\ProtoBuffers;


class ProtobufEnum {

    public static function toString($value) {
        if (is_null($value))
            return null;
        if (array_key_exists($value, self::$_values))
            return self::$_values[$value];
        return 'UNKNOWN';
    }
}
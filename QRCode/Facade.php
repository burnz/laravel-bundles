<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/26
 * Time: 01:59
 */

namespace Xjtuwangke\QRCode;


class Facade extends \Illuminate\Support\Facades\Facade{

    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor(){
        return 'qrcode.factory';
    }
}
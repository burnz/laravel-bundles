<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/21
 * Time: 04:44
 */

namespace Xjtuwangke\Sms;


class Facade extends \Illuminate\Support\Facades\Facade{

    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor(){
        return 'sms.sender';
    }
}
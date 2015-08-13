<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/12
 * Time: 06:23
 */

namespace Xjtuwangke\Sms;


interface SenderContract {

    /**
     * @param $to
     * @param $message
     * @return mixed
     */
    public function send( $to , $message );

}
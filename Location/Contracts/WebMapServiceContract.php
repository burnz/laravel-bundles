<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 19:24
 */

namespace Xjtuwangke\Location\Contracts;


interface WebMapServiceContract {

    const Target_PC     = 0;
    const Target_Mobile = 1;

    /**
     * @param LocationContract $location
     * @param int              $target
     * @return string
     */
    public static function url( LocationContract $location , $target = WebMapServiceContract::Target_PC );

}
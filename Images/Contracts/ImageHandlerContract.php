<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 21:03
 */

namespace Xjtuwangke\Images\Contracts;


interface ImageHandlerContract {

    public function getWidth( $image );

    public function getHeight( $image );
}
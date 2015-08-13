<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/15
 * Time: 03:14
 */

namespace Xjtuwangke\Utils;


class Rectangle{

    public $width;

    public $height;

    public $absX;

    public $abxY;

    public function __construct( $width , $height , $absX = 0 , $absY = 0 ){
        $this->width = $width;
        $this->height = $height;
        $this->absX = $absX;
        $this->abxY = $absY;
    }

}
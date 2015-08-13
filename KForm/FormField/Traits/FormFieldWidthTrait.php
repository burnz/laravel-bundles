<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 17:00
 */

namespace Xjtuwangke\KForm\FormField\Traits;


trait FormFieldWidthTrait {

    protected $width = 1;

    /**
     * @param $width
     * @return $this
     */
    public function setWidth( $width ){
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(){
        return $this->width;
    }

    /**
     * @return string
     */
    public function getColClass(){
        $col = ceil( $this->width * 12.0 );
        return "col-md-{$col} col-lg-{$col}";
    }

}
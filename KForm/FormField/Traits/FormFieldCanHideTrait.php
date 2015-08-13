<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 19:32
 */

namespace Xjtuwangke\KForm\FormField\Traits;


trait FormFieldCanHideTrait {

    /**
     * @var bool
     */
    protected $isHide = false;

    /**
     * @param bool $hide
     * @return $this
     */
    public function setHide( $hide = true ){
        $this->isHide = ( true == $hide );
        return $this;
    }

    /**
     * @return bool
     */
    public function isHide(){
        return $this->isHide;
    }
}
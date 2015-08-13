<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 16:59
 */

namespace Xjtuwangke\KForm\FormField\Traits;


trait FormFieldOptionsTrait {

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param array $array
     * @return $this
     */
    public function setOptions( array $array ){
        $this->options = $array;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(){
        return $this->options;
    }

}
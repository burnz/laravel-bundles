<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 19:26
 */

namespace Xjtuwangke\KForm\FormField\Traits;


trait FormFieldPlaceholderTrait {

    protected $placeholder;

    public function setPlaceholder( $placeholder ){
        $this->placeholder = (string) $placeholder;
    }

    public function getPlaceholder(){
        return $this->placeholder;
    }

}
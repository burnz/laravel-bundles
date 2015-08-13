<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/6
 * Time: 16:23
 */

namespace Xjtuwangke\KForm\FormField\Types;


use Xjtuwangke\KForm\FormField\FormField;

class AreaSelect extends FormField{

    public function getType(){
        return 'areaselect';
    }

    public function getAreaProvince(){
        return $this->getAreaLevel(0);
    }

    public function getAreaCity(){
        return $this->getAreaLevel(1);
    }

    public function getAreaDistinct(){
        return $this->getAreaLevel(2);
    }

    public function getAreaLevel( $i ){
        $value = $this->getValue();
        if( is_array( $value ) && array_key_exists( $i , $value ) ){
            return $value[$i];
        }
        else{
            return null;
        }
    }

}
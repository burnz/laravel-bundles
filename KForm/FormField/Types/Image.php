<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/5
 * Time: 13:47
 */

namespace Xjtuwangke\KForm\FormField\Types;


use Xjtuwangke\KForm\FormField\FormField;

class Image extends FormField{

    /**
     * @var string
     */
    protected $image_type = 'default';

    public function getType(){
        return 'image';
    }

    /**
     * @return string
     */
    public function getImageType(){
        return $this->image_type;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setImageType( $type ){
        $this->image_type = $type;
        return $this;
    }



}
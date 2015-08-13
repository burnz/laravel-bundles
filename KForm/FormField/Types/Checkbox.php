<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/8
 * Time: 17:21
 */

namespace Xjtuwangke\KForm\FormField\Types;


use Xjtuwangke\KForm\FormField\FormField;
use Xjtuwangke\KForm\FormField\Traits\FormFieldOptionsTrait;

class Checkbox extends FormField{

    protected $default = array();

    use FormFieldOptionsTrait;

    public function getType(){
        return 'checkbox';
    }
}
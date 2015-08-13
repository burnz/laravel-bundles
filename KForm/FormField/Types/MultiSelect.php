<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/21
 * Time: 03:16
 */

namespace Xjtuwangke\KForm\FormField\Types;
use Xjtuwangke\KForm\FormField\FormField;

use Xjtuwangke\KForm\FormField\Traits\FormFieldOptionsTrait;

class MultiSelect extends FormField{

    use FormFieldOptionsTrait;

    public function getType(){
        return 'multiselect';
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/21
 * Time: 03:15
 */

namespace Xjtuwangke\KForm\FormField\Types;
use Xjtuwangke\KForm\FormField\FormField;

use Xjtuwangke\KForm\FormField\Traits\FormFieldOptionsTrait;

class Select extends FormField{

    use FormFieldOptionsTrait;

    public function getType(){
        return 'select';
    }

}
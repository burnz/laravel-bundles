<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/26
 * Time: 17:46
 */

namespace Xjtuwangke\KForm\FormField\Types;
use Xjtuwangke\KForm\FormField\FormField;

use Xjtuwangke\KForm\FormField\Traits\FormFieldPlaceholderTrait;


class Date extends FormField{

    use FormFieldPlaceholderTrait;

    public function getType(){
        return 'date';
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/21
 * Time: 03:14
 */

namespace Xjtuwangke\KForm\FormField\Types;
use Xjtuwangke\KForm\FormField\FormField;

use Xjtuwangke\KForm\FormField\Traits\FormFieldPlaceholderTrait;

class Text extends FormField{

    use FormFieldPlaceholderTrait;

    public function getType(){
        return 'text';
    }

}
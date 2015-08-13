<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/21
 * Time: 03:15
 */

namespace Xjtuwangke\KForm\FormField\Types;
use Xjtuwangke\KForm\FormField\FormField;
use Xjtuwangke\KForm\FormField\Traits\FormFieldPlaceholderTrait;

class TextArea extends FormField{

    use FormFieldPlaceholderTrait;

    /**
     * textarea的行数
     * @var int
     */
    protected $rows = 5;

    public function getType(){
        return 'textarea';
    }

    public function setRows( $rows ){
        $this->rows = (int) $rows;
        return $this;
    }

    public function getRows(){
        return $this->rows;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/21
 * Time: 01:46
 */

namespace Xjtuwangke\KForm\FormField;

use Xjtuwangke\KForm\FormField\Traits\FormFieldCanHideTrait;
use Xjtuwangke\KForm\FormField\Traits\FormFieldLabelTrait;
use Xjtuwangke\KForm\FormField\Traits\FormFieldValueTrait;
use Xjtuwangke\KForm\FormField\Traits\FormFieldWidthTrait;

abstract class FormField {

    use FormFieldValueTrait;
    use FormFieldLabelTrait;
    use FormFieldWidthTrait;
    use FormFieldCanHideTrait;

    /**
     * @var string fieldname
     */
    protected $fieldName = '';

    /**
     * @param $name
     * @return $this
     */
    public function setFieldName( $fieldName ){
        $this->fieldName = ( string ) $fieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldName(){
        return $this->fieldName;
    }

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    public function render(){
        return view( 'xjtuwangke-kform::' . $this->getType() )->with( 'field' , $this );
    }

    /**
     * @return mixed
     */
    public function formgroup(){
        $hidden = $this->isHide()?'display:none;':'';
        $hasError = $this->hasError()?'has-error':'';
        $options = array(
            'class' => 'form-group' ,
            'style' => '' ,
        );
        $options['class'].= ' '.$hasError;
        $options['class'].= ' '.$this->getColClass();
        $options['style'].= ' '.$hidden;
        return \HTML::attributes( $options );
    }
}
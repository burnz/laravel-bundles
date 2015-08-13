<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/21
 * Time: 01:45
 */

namespace Xjtuwangke\KForm;


use Xjtuwangke\KForm\FormField\FormField;
use Xjtuwangke\KForm\Traits\FormMappingTrait;
use Xjtuwangke\KForm\Traits\FormRowTrait;
use Xjtuwangke\KForm\Traits\FormRulesTrait;
use Xjtuwangke\KForm\Traits\FormTailTrait;
use Xjtuwangke\KForm\FormField\Types\Text;

class KForm implements SessionFlashedKFormContract{

    use FormRowTrait;
    use FormTailTrait;
    use FormRulesTrait;
    use FormMappingTrait;

    public static $session_flash_key = '_kform_instance';

    /**
     * @var array[FormField]
     */
    protected $formFileds = array();

    /**
     * @return array
     */
    public function getFormFields(){
        return $this->formFileds;
    }

    /**
     * @param $field
     * @return null | FormField
     */
    public function getFormField( $field ){
        foreach( $this->formFileds as $formfield ){
            if( $field == $formfield->getFieldName() ){
                return $formfield;
            }
        }
        return null;
    }

    /**
     * @param $field
     * @return mixed|null
     */
    public function hasError( $field ){
        $errors = $this->getError( $field , null );
        if( is_null( $errors ) ){
            return false;
        }
        elseif( empty( $errors ) ){
            return false;
        }
        else{
            return true;
        }
    }

    /**
     * 快速得到某个formfield的value
     * @param $field
     * @param array $default
     * @return array|null
     */
    public function getValue( $field , $default = null ){
        if( $this->getFormField( $field ) ){
            $value = $this->getFormField( $field )->getValue();
            if( $value ){
                return $value;
            }
            else{
                return $default;
            }
        }
        else{
            return $default;
        }
    }

    /**
     * @param FormField $formField
     * @return $this
     */
    public function addFormField( FormField $formField ){
        $i = count( $this->formFileds );
        $this->formFileds[$i] = $formField;
        $this->addToCurrentRow( $formField , $i );
        return $this;
    }

    /**
     * @param $name
     * @param array $rules
     * @param null $default
     * @return $this
     */
    public function addSimpleField( $name , $rules = array() , $default = null ){
        $field = new Text();
        $field->setFieldName( $name )->setRules( $rules )->setDefault( $default );
        $this->addFormField( $field );
        return $this;
    }

    /**
     * @param array $options
     * @return string
     */
    public function render( array $options = array() ){
        if( array_key_exists( 'class' , $options ) ){
            $options['class'].= ' form-horizontal';
        }
        else{
            $options['class'] = 'form-horizontal';
        }
        if( ! array_key_exists( 'role' , $options ) ){
            $options['role'] = 'form';
        }
        $form = \Form::open( $options );
        $displayed = array();
        foreach( $this->rows as $row ){
            $form.= '<div class="row">';
            foreach( $row as $field_name ){
                $field = $this->getFormField( $field_name );
                if( $field ){
                    $form.= $field->render();
                    $displayed[] = $field_name;
                }
            }
            $form.= '</div>';
        }
        foreach( $this->getFormFields() as $field_name => $field  ){
            if( ! in_array( $field_name , $displayed ) ){
                $form.= '<div class="row">';
                $form.= $field->render();
                $form.= '</div>';
            }
        }
        $form.= $this->getTail();
        return $form;
    }

}
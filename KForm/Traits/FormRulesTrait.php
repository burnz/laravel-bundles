<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 16:09
 */

namespace Xjtuwangke\KForm\Traits;


use Xjtuwangke\KForm\FormField\FormField;

trait FormRulesTrait {

    /**
     * @return array
     */
    public function getRulesKey(){
        $rules = array();
        foreach( $this->formFileds as $formField ){
            if( $formField instanceof FormField ){
                $rules[ $formField->getFieldName() ] = $formField->getRulesKey();
            }
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function getRulesMessage(){
        $rules = array();
        foreach( $this->formFileds as $formField ){
            if( $formField instanceof FormField ){
                $rules[ $formField->getFieldName() ] = $formField->getRules();
            }
        }
        return $rules;
    }

    /**
     * @return bool
     */
    public function isPassed(){
        foreach( $this->getFormFields() as $formField ){
            if( $formField instanceof FormField && ! $formField->isPassed() ){
                return false;
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public function getErrors(){
        $errors = array();
        foreach( $this->getFormFields() as $formField ){
            if( $formField instanceof FormField && $formField->hasError() ){
                $errors[ $formField->getFieldName() ] = $formField->getErrors();
            }
        }
        return $errors;
    }

    /**
     * @param $field
     * @param array $default
     * @return array
     */
    public function getError( $field , $default = array() ){
        if( $field = $this->getFormField( $field ) ){
            return $field->getErrors();
        }
        else{
            return $default;
        }
    }

    /**
     * @param $field
     * @param $error
     * @return $this
     */
    public function addError( $field , $error ){
        if( $field = $this->getFormField( $field ) ){
            $field->addError( $error );
        }
        return $this;
    }

}
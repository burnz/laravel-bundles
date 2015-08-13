<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 17:00
 */

namespace Xjtuwangke\KForm\FormField\Traits;


trait FormFieldErrorMessageTrait {

    /**
     * @var array | null
     */
    protected $errors;

    /**
     * @var bool
     */
    protected $passed = true;

    /**
     * @param array $errors
     * @return $this
     */
    public function setErrors( array $errors ){
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param $error
     * @return $this
     */
    public function addError( $error ){
        $errors = $this->getErrors();
        $errors[] = $error;
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param array $errors
     * @return $this
     */
    public function mergeErrors( array $errors ){
        $this->errors = array_merge( $this->getErrors() , $errors );
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(){
        if( ! is_array( $this->errors ) ){
            $this->errors = array();
        }
        return $this->errors;
    }

    /**
     * @return $this
     */
    public function failValidation(){
        $this->passed = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function passValidation(){
        $this->passed = true;
        $this->errors = array();
        return $this;
    }

    /**
     * @return bool
     */
    public function isPassed(){
        return $this->passed;
    }

    /**
     * @return bool
     */
    public function hasError(){
        return ! empty( $this->getErrors() );
    }
}
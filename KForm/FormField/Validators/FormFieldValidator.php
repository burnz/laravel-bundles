<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 17:30
 */

namespace Xjtuwangke\KForm\FormField\Validators;


use Illuminate\Http\Request;
use Xjtuwangke\KForm\FormField\FormField;

abstract class FormFieldValidator {

    /**
     * 验证不通过时的错误提示
     * @var string
     */
    protected $errorMessage = '表单输入不合法';

    /**
     * @var
     */
    protected $rule;

    /**
     * @param null $rule
     * @param null $errorMessage
     */
    public function __construct( $rule = null , $errorMessage = null){
        if( ! is_null( $errorMessage ) ){
            $this->errorMessage = $errorMessage;
        }
        $this->rule = $rule;
    }

    /**
     * @param $value
     * @param Request|null $request
     * @return mixed
     */
    abstract public function doValidate( $value , Request $request = null );

    public function validate( FormField $formField , Request $request = null ){
        $value = $formField->getValue();
        if( false == $this->doValidate( $value , $request ) ){
            $formField->addError( $this->errorMessage );
            return false;
        }
        else{
            return true;
        }
    }
}
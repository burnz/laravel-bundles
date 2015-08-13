<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 16:59
 */

namespace Xjtuwangke\KForm\FormField\Traits;


use Xjtuwangke\KForm\FormField\Validators\FormFieldValidator;
use Xjtuwangke\KForm\FormField\ValueFilters\ValueFilter;
use Illuminate\Http\Request;

trait FormFieldValueTrait {

    use FormFieldErrorMessageTrait;

    /**
     * @var null field value
     */
    protected $value = null;

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @var null 默认值
     */
    protected $default = null;

    /**
     * @var boolean 固定值
     */
    protected $fixed = false;

    /**
     * @var array
     */
    protected $valueValidators = array();

    /**
     * @var array
     */
    protected $valueFilters = array();

    /**
     * 设定默认值
     * @param $value
     * @return $this
     */
    public function setDefault( $value ){
        if( $this instanceof FormFieldHasMultipleValuesTrait ){
            if( is_null( $value ) ){
                $value = array();
            }
            if( ! is_array( $value ) ){
                $value = [ $value ];
            }
        }
        $this->default = $value;
        return $this;
    }

    /**
     * 返回表单域的值
     * @return null
     */
    public function getValue(){
        if( $this->isFixed() ){
            return $this->default;
        }
        if( is_null( $this->value ) && ! is_null( $this->default ) ){
            return $this->default;
        }
        return $this->value;
    }

    /**
     * @param bool $fixed
     * @return $this
     */
    public function setFixed( $fixed = true ){
        $this->fixed = ( true == $fixed );
        return $this;
    }

    /**
     * 是否固定到default值
     * @return bool
     */
    public function isFixed(){
        return true == $this->fixed;
    }

    /**
     * 设置验证过滤规则
     * @param $rules array|string
     * @return $this
     */
    public function setRules( $rules ){
        if( ! is_array( $rules ) ){
            $rules = explode( '|' , $rules );
        }
        $this->rules = $rules;
        return $this;
    }

    /**
     * 增加数据过滤器类
     * @param ValueFilter $filter
     * @return $this
     */
    public function addValueFilter( ValueFilter $filter ){
        $this->valueFilters[] = $filter;
        return $this;
    }

    /**
     * 增加数据验证器类
     * @param FormFieldValidator $validator
     * @return $this
     */
    public function addValueValidator( FormFieldValidator $validator ){
        $this->valueValidators[] = $validator;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getRules(){
        $rules = array();
        foreach( $this->rules as $key => $val ){
            $array = explode( ':' , $key );
            $rules[$array[0] ] = $val;
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function getRulesKey(){
        return array_keys( $this->rules );
    }

    /**
     * @param string $pattern 需要匹配的正则表达式
     * @param string $msg 匹配不通过的错误提示
     * @return $this
     */
    public function setRegxRule( $pattern , $msg = '输入不符合规则'){
        $this->rules[ 'regex:' . $pattern ] = $msg;
        return $this;
    }

    /**
     * 为表单设定值
     * @param $value
     * @param Request|null $request
     * @return $this
     */
    public function setValue( $value , Request $request = null ){
        if( $this->isFixed() ){
            return $this;
        }
        foreach( $this->valueFilters as $filter ){
            $filter = new $filter;
            if( $filter instanceof ValueFilter ){
                $value = $filter->filter( $value );
            }
        }
        $this->value = $value;
        $this->validate( $request );
        return $this;
    }

    /**
     * @param Request|null $request
     * @return bool
     */
    public function validate( Request $request = null ){
        //首先跑$this->valueValidators中的方法
        $result = true;
        $this->passValidation();
        foreach( $this->valueValidators as $validator ){
            if( false == $validator->validate( $this ) ){
                $this->failValidation();
                $result = false;
            }
        }
        //再跑$this->rules中的规则
        $field = $this->getFieldName();
        if( $request ){
            $data = $request->all();
        }
        else{
            $data = array();
        }
        $data[ $field ] = $this->getValue();
        $rules = array(
            $field => $this->getRulesKey() ,
        );

        $messages = $this->getRules();
        $validator = \Validator::make( $data , $rules , $messages );
        if( $validator->fails() ){
            $errors = $validator->errors()->get( $field );
            if( is_array( $errors ) ){
                $this->mergeErrors( $errors );
            }
            else{
                $this->addError( $errors );
            }
            $this->failValidation();
            $result= false;
        }
        return $result;
    }
}
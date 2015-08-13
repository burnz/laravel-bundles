<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 16:58
 */

namespace Xjtuwangke\KForm\DataMapping;

use Illuminate\Database\Eloquent\Model;
use Xjtuwangke\BugSnag\Exception;
use Xjtuwangke\KForm\FormField\FormField;
use Xjtuwangke\KForm\KForm;

class SingleEloquentInstance implements InstanceMappingWithFormContract{

    /**
     * @var Model
     */
    protected $instance;

    /**
     * @var \ReflectionObject
     */
    protected $instanceReflector;

    /**
     * @param Model $instance
     */
    public function __construct( Model $instance ){
        $this->instance = $instance;
        $this->instanceReflector = new \ReflectionObject( $this->instance );
    }

    /**
     * @param FormField $formField
     * @param KForm     $form
     * @throws Exception
     */
    public function instanceMapTo( FormField $formField , KForm $form ){
        $attribute = $formField->getFieldName();
        $method = 'mappingToFormField' . ucfirst( $attribute );
        if( $this->instanceReflector->hasMethod( $method ) ){
            $methodReflector = $this->instanceReflector->getMethod( $method );
            $parameters = array();
            foreach( $methodReflector->getParameters() as $paramReflector ){
                switch( true ){
                    case $paramReflector->getClass()->isSubclassOf( KForm::class ):
                        $parameters[] = $form;
                        break;
                    case $paramReflector->getClass()->isSubclassOf( FormField::class ):
                        $parameters[] = $formField;
                        break;
                    default:
                        $parameters[] = null;
                        break;
                }
            }
            $default = call_user_func_array( array( $this->instance , $method ) , $parameters );
        }
        else{
            if( ! $attribute || ! is_string( $attribute ) ){
                throw new Exception('表单从模型映射数据时,fieldname不是一个合法的字符串:' . serialize( $attribute ));
            }
            $default = $this->instance->{$attribute};
        }
        $formField->setDefault( $default );
    }

    /**
     * @param FormField $formField
     * @param KForm     $form
     */
    public function instanceMapFrom( FormField $formField , KForm $form ){
        $attribute = $formField->getFieldName();
        $method = 'mappingFromFormField' . ucfirst( $attribute );
        if( $this->instanceReflector->hasMethod( $method ) ){
            $methodReflector = $this->instanceReflector->getMethod( $method );
            $parameters = array();
            foreach( $methodReflector->getParameters() as $paramReflector ){
                switch( true ){
                    case $paramReflector->getClass()->isSubclassOf( KForm::class ):
                        $parameters[] = $form;
                        break;
                    case $paramReflector->getClass()->isSubclassOf( FormField::class ):
                        $parameters[] = $formField;
                        break;
                    default:
                        $parameters[] = $formField;
                        break;
                }
            }
            call_user_func_array( array( $this->instance , $method ) , $parameters );
        }
        else{
            $this->instance->{$attribute} = $formField->getValue();
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/4
 * Time: 22:53
 */

namespace Xjtuwangke\KForm\Dumper;


use Illuminate\Database\Schema\Blueprint;
use Xjtuwangke\KForm\KFormFactory;

class FormBluePrint extends Blueprint {

    /**
     * @override
     */
    public function __construct(){}

    /**
     * 返回form的配置数组
     * @return array
     */
    public function dump(){
        $form = array();
        foreach( $this->columns as $column ){
            if( $column instanceof \Illuminate\Support\Fluent ){
                $name = $column->get('name');
                if( ! $label = $column->get('label') ){
                    continue;
                }
                $field = array();
                $rules = array();
                $field['label'] = $label;
                if( true != $column->get('nullable')){
                    $rules['required'] = $label . '不能为空';
                }
                switch( $column->get('type') ) {
                    case 'integer':
                        $field['type'] = KFormFactory::Text;
                        $rules['integer'] = $label . '必须为数字';
                        if (true == $column->get('unsigned')) {
                            $rules['min:0'] = $label . '必须为大于零的数字';
                        }
                        break;
                    case 'string':
                        $field['type'] = KFormFactory::Text;
                        break;
                    case 'enum':
                        $field['type'] = KFormFactory::Select;
                        $field['options'] = array_flip( $column->get( 'allowed' , array() ) );
                        break;
                    case 'date':
                        $field['type'] = KFormFactory::Date;
                        break;
                    case 'text':
                        $field['type'] = KFormFactory::TextArea;
                        break;
                    default:
                        continue;
                }
                $field['rules'] = $rules;
                $form[ $name ] = $field;
            }
        }
        return $form;
    }
}
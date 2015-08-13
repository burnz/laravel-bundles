<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/8
 * Time: 15:07
 */

namespace Xjtuwangke\Admin\FormRequests;


use Xjtuwangke\KForm\FormRequest\KFormRequest;
use Xjtuwangke\KForm\KForm;
use Xjtuwangke\KForm\FormField\Types\Text;

class RoleControllerFormRequest extends KFormRequest {

    protected function generateKform(){
        $form = new KForm();
        $field = new Text();
        $field->setFieldName( 'name' )
            ->setRules(array(
                'required' => '角色名不能为空' ,
                'min:3'    => '角色名不能小于3个字符' ,
                'max:100'   => '角色名不能大于100个字符' ,
            ))
            ->setWidth(1)
            ->setDefault(null)
            ->setLabel('请输入角色名');
        $form->addFormField( $field );

        $field = new Text();
        $field->setFieldName( 'display_name' )
            ->setRules(array(
                'required' => '描述不能为空' ,
                'min:5'    => '描述不能小于5个字符' ,
                'max:100'   => '描述不能大于100个字符' ,
            ))
            ->setWidth(1)
            ->setDefault(null)
            ->setLabel('请输入描述');
        $form->addFormField( $field );
        return $form;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/15
 * Time: 18:50
 */

namespace Xjtuwangke\Admin\FormRequests;

use Xjtuwangke\KForm\FormField\Types\Password;
use Xjtuwangke\KForm\FormField\Types\Select;
use Xjtuwangke\KForm\FormField\Types\Text;
use Xjtuwangke\KForm\FormRequest\KFormRequest;
use Xjtuwangke\KForm\KForm;

class PasswordFormRequest extends KFormRequest
{

    protected function generateKform(){

        $kform = new KForm();

        $formfield = new Password();
        $formfield->setFieldName( 'old_password' );
        $formfield->setLabel( '请输入您的旧密码(*)' );
        $formfield->setRules(array(
            'required' => '必填' ,
        ));
        $kform->addFormField( $formfield );

        $formfield = new Password();
        $formfield->setFieldName( 'password' );
        $formfield->setLabel( '请输入您的新密码(*)' );
        $formfield->setRules(array(
            'required' => '必填' ,
            'confirmed' => '两次输入必须一致' ,
            'min:6' => '密码长度不应小于6位' ,
            'max:32' => '密码长度不应大于32位' ,
        ));
        $kform->addFormField( $formfield );

        $formfield = new Password();
        $formfield->setFieldName( 'password_confirmation' );
        $formfield->setLabel( '请再输入一次(*)' );
        $formfield->setRules(array(
            'required' => '必填' ,
            'min:6' => '密码长度不应小于6位' ,
            'max:32' => '密码长度不应大于32位' ,
        ));
        $kform->addFormField( $formfield );

        return $kform;
    }
}
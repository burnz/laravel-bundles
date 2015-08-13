<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/15
 * Time: 18:33
 */

namespace Xjtuwangke\Admin\FormRequests;


use Xjtuwangke\KForm\FormField\Types\Password;
use Xjtuwangke\KForm\FormField\Types\Select;
use Xjtuwangke\KForm\FormField\Types\Text;
use Xjtuwangke\KForm\FormRequest\KFormRequest;
use Xjtuwangke\KForm\KForm;

class ProfileFormRequest extends KFormRequest
{

    protected function generateKform(){
        $kform = new KForm();

        $formfield = new Text();
        $formfield->setFieldName( 'username' );
        $formfield->setLabel( '请输入您的姓名(*)' );
        $formfield->setRules(array(
            'required' => '请输入您的姓名' ,
            'min:2' => '最少2个字符' ,
            'max:10' => '最长10个字符' ,
        ));

        $kform->addFormField( $formfield );

        $formfield = new Password();
        $formfield->setFieldName( 'old_password' );
        $formfield->setLabel( '请输入您的密码(*)' );
        $formfield->setRules(array(
            'required' => '必填' ,
        ));

        $kform->addFormField( $formfield );

        $formfield = new Text();
        $formfield->setFieldName( 'email' );
        $formfield->setLabel( '请输入您的邮箱(*)' );
        $formfield->setRules(array(
            'required' => '必填' ,
            'email' => '请输入合法的邮箱' ,
        ));

        $kform->addFormField( $formfield );

        $formfield = new Text();
        $formfield->setFieldName( 'mobile' );
        $formfield->setLabel( '请输入您的手机(*)' );
        $formfield->setRules(array(
            'required' => '必填' ,
            'mobile' => '请输入合法的手机' ,
        ));

        $kform->addFormField( $formfield );

        $formfield = new Select();
        $formfield->setFieldName( 'gender' );
        $formfield->setLabel( '请输入您的性别' );
        $formfield->setOptions(array(
            '未填' => '未填' ,
            '男' => '男' ,
            '女' => '女' ,
        ));

        $kform->addFormField( $formfield );

        return $kform;

    }
}
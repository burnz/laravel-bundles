<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 21:52
 */

$form = new \Xjtuwangke\KForm\KForm();

$field = new Xjtuwangke\KForm\FormField\Types\Text();
$field->setFieldName( 'login' )
    ->setRules(array(
        'required' => '用户名不能为空' ,
        'min:5'    => '用户名不能小于5个字符' ,
        'max:10'   => '用户名不能大于10个字符' ,
    ))
    ->setWidth(1)
    ->setDefault(null)
    ->setLabel('请输入用户名');
$form->addFormField( $field );

$field = new \Xjtuwangke\KForm\FormField\Types\Password();
$field->setFieldName('password')
    ->setRules(array(
        'required' => '密码不能为空' ,
        'min:8'    => '密码不能小于8个字符' ,
        'max:32'   => '密码不能大于32个字符' ,
    ))
    ->setWidth(1)
    ->setDefault(null)
    ->setLabel('请输入密码');
$form->addFormField( $field );

return $form;
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 17:03
 */

$form = new \Xjtuwangke\KForm\KForm();

$field = new Xjtuwangke\KForm\FormField\Types\Text();
$field->setFieldName( 'username' )
    ->setRules(array(
        'required' => '用户名不能为空' ,
        'min:3'    => '用户名不能小于3个字符' ,
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
        'min:3'    => '密码不能小于3个字符' ,
        'max:10'   => '密码不能大于10个字符' ,
    ))
    ->setWidth(1)
    ->setDefault(null)
    ->setLabel('请输入密码');
$form->addFormField( $field );

$field = new \Xjtuwangke\KForm\FormField\Types\MultiSelect();
$field->setFieldName( 'favorites' )->setOptions( ['西瓜','苹果','鸭梨'])->setLabel( '选择你最喜爱的水果' );

$form->addFormField( $field );

$field = new \Xjtuwangke\KForm\FormField\Types\TextArea();
$field->setFieldName( 'favorites' )->setLabel( '选择你最喜爱的水果' );
$form->addFormField( $field );

return $form;
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/15
 * Time: 18:27
 */

namespace Xjtuwangke\Admin\Controllers;

use Auth;
use Xjtuwangke\Admin\FormRequests\PasswordFormRequest;
use Xjtuwangke\Admin\FormRequests\ProfileFormRequest;
use Xjtuwangke\KForm\DataMapping\SingleEloquentInstance;
use Xjtuwangke\KForm\KForm;
use Xjtuwangke\KForm\SessionFlashedKFormContract;
use Illuminate\Http\Exception\HttpResponseException;

class ProfileController extends  AdminController{

    protected $user;

    protected function checkAuthedUser(){
        $this->user = Auth::user();
        if( ! $this->user ){
            throw new HttpResponseException(redirect()->to('admin/login') );
        }
        if( ! $this->user->hasRole('admin') ){
            throw new HttpResponseException(redirect()->to('admin/forbidden') );
        }
    }

    public function profile( SessionFlashedKFormContract $form ){
        $this->checkAuthedUser();
        if( ! $form instanceof KForm ){
            $form = new ProfileFormRequest();
            $form = $form->getKform();
        }
        $mapping = new SingleEloquentInstance( $this->user );
        $form->addMappingInstance( $mapping );
        $form->mapFromInstance();
        $this->getLayout();
        $this->layout->title = '修改个人信息';
        $this->layout->content = $form->render();
        return $this->layout;
    }

    public function postProfile( ProfileFormRequest $request ){
        $this->checkAuthedUser();
        $user = $this->user;
        $form = $request->getKform();
        $password = $form->getValue( 'old_password' );
        if( \Hash::check( $password , $user->getAuthPassword() ) ){
            $user->username = $form->getValue( 'username' );
            $user->email    = $form->getValue( 'email' );
            $user->mobile   = $form->getValue( 'mobile' );
            $user->gender   = $form->getValue( 'gender' );
            $user->save();
            return $this->redirectToMethod( 'success' , $this );
        }
        else{
            $form->addError( 'old_password' , '密码不正确' );
            return $request->redirectBackResponse();
        }
    }

    public function password( SessionFlashedKFormContract $form ){
        $this->checkAuthedUser();
        if( ! $form instanceof KForm ){
            $form = new PasswordFormRequest();
            $form = $form->getKform();
        }
        $this->getLayout();
        $this->layout->title = '修改密码';
        $this->layout->content = $form->render();
        return $this->layout;
    }

    public function postPassword( PasswordFormRequest $request ){
        $this->checkAuthedUser();
        $user = $this->user;
        $form = $request->getKform();
        $password = $form->getValue( 'old_password' );
        if( \Hash::check( $password , $user->getAuthPassword() ) ){
            $user->password = \Hash::make( $form->getValue( 'password') );
            $user->save();
            return $this->redirectToMethod( 'success' , $this );
        }
        else{
            $form->addError( 'old_password' , '密码不正确' );
            return $request->redirectBackResponse();
        }
    }

    public function success(){
        $this->checkAuthedUser();
        $this->getLayout();
        $this->layout->title = '信息修改成功';
        $this->layout->conent = '<h3>信息修改成功</h3>';
        return $this->layout;
    }
}
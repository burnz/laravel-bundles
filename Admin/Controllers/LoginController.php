<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 18:41
 */

namespace Xjtuwangke\Admin\Controllers;

use Xjtuwangke\Admin\FormRequests\LoginFormRequest;
use Xjtuwangke\KForm\KForm;
use Xjtuwangke\KForm\SessionFlashedKFormContract;
use Xjtuwangke\LaravelModels\UserModel;

class LoginController extends AdminController{

    /**
     * @return KForm;
     */
    public static function getLoginForm(){
        return config('admin.login_form');
    }

    /**
     * @return \Illuminate\View\View
     */
    protected function getLayout(){
        if( is_null( $this->layout ) ){
            $this->assets = new AdminAssets();
            $this->layout = $this->makeView('admin-lte/login');
            $this->layout->content = '';
            $this->layout->title = '';
            $this->layout->site_name = \Config::get( 'xjtuwangke-admin::site.name' );
        }
        return $this->layout;
    }

    /**
     * @param SessionFlashedKFormContract $loginForm
     * @return View
     */
    public function login( SessionFlashedKFormContract $loginForm ){
        if( ! $loginForm instanceof KForm ){
            $loginForm = $this->getLoginForm();
        }
        return $this->getLayout()->with('form' , $loginForm );
    }

    public function login_action( LoginFormRequest $request ){
        $try = UserModel::attempt( array( 'mobile' => $request->get('login') , 'password' => $request->get('password') ) );
        if( $try ){
            LockController::setLocked( false );
            return redirect('admin/index');
        }
        else{
            return redirect()->back();
        }
    }

    public function logout(){
        \Auth::logout();
        LockController::setLocked( false );
        return $this->redirectToMethod( 'login' );
    }

}
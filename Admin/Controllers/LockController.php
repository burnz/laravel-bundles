<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/13
 * Time: 01:12
 */

namespace Xjtuwangke\Admin\Controllers;


use Xjtuwangke\LaravelModels\UserModel;
use Session;

class LockController extends AdminController{

    /**
     * @return \Illuminate\View\View
     */
    protected function getLayout(){
        if( is_null( $this->layout ) ){
            $this->assets = new AdminAssets();
            $this->layout = $this->makeView('admin-lte/locked');
            $this->layout->content = '';
            $this->layout->title = '';
            $this->layout->site_name = \Config::get( 'xjtuwangke-admin::site.name' );
        }
        return $this->layout;
    }

    /**
     * @param bool|true $locked
     */
    public static function setLocked( $locked = true ){
        Session::set( 'admin_panel_locked' , $locked );
    }

    /**
     * @return mixed
     */
    public static function isLocked(){
        return Session::get('admin_panel_locked');
    }

    /**
     * @param bool|false $error
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function lock( $error = false ){
        $user = \Auth::user();
        if( ! $user ){
            return $this->redirectToMethod( 'login' , new LoginController() );
        }
        static::setLocked();
        return $this->getLayout()->with( 'user' , $user )->with( 'error' , $error );
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse|LockController
     */
    public function unlock(){
        $user = \Auth::user();
        if( ! $user ){
            return $this->redirectToMethod( 'login' , new LoginController() );
        }
        $password = \Input::get( 'password' );
        if( \Hash::check( $password , $user->password ) ){
            static::setLocked( false );
            return redirect()->to('admin');
        }
        else{
            return $this->lock( true );
        }
    }
}
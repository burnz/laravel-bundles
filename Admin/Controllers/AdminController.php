<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 18:42
 */

namespace Xjtuwangke\Admin\Controllers;

use Xjtuwangke\Admin\Elements\KMenu;
use Xjtuwangke\Admin\Middleware\AdminAuthMiddleware;
use Xjtuwangke\L5Controller\L5ViewController;
use Xjtuwangke\LaravelModels\UserModel;

abstract class AdminController extends L5ViewController{

    /**
     * @param $view
     * @return \Illuminate\Contracts\View\View
     */
    public function makeView( $view ){
        return parent::makeView( 'xjtuwangke-admin::' . $view );
    }

    /**
     * @return \Illuminate\View\View
     */
    protected function getLayout(){
        if( is_null( $this->layout ) ){
            $this->assets = new AdminAssets();
            $this->layout = $this->makeView('admin-lte/layout');
            $this->layout->content = '';
            $this->layout->title = '';
            $this->layout->site_name = \Config::get( 'xjtuwangke-admin::site.name' );

            /*
            $user = AuthModel::getUser();
            $menu = new AdminMenu( $user );

            if( isset( static::$menu_name ) ){
                $menu_name = static::$menu_name;
            }
            elseif( isset( static::$name ) ){
                $menu_name = static::$name;
            }
            else{
                $menu_name = null;
            }

            $this->layout->shortcuts = $menu->getCurrentSubMenu( $menu_name );

            $menu = $menu->getMenu();

            $this->layout->navbar = View::make( 'laravel-cms::admin-lte/navbar' )->with( 'menu' , $menu );
            $this->layout->usermenu = View::make( 'laravel-cms::admin-lte.user_menu' )->with( 'user' , AuthModel::getUser() );
            */
            $user = \Auth::user();
            $menu = KMenu::make( $user );
            $this->layout->navbar = $this->makeView('admin-lte/navbar')->with( 'menu' , $menu );
        }
        return $this->layout;
    }

}
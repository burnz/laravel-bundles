<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-26
 * Time: 16:51
 */

namespace Xjtuwangke\LaravelCms\Auth;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class AdminMenu {

    protected $menu = null;

    protected $user = null;

    public function __construct( $user ){
        $this->generateForUser( $user );
    }

    public function getMenu(){
        return $this->menu;
    }

    public function getCurrentSubMenu( $menu_name ){
        foreach( $this->menu as $menu ){
            if( $menu->active && property_exists( $menu , 'submenu' ) ){
                return $menu->submenu;
            }
        }
        foreach( $this->menu as $menu ){
            if( $menu->title == $menu_name && property_exists( $menu , 'submenu' ) ){
                return $menu->submenu;
            }
        }

        return array();
    }

    public function generateForUser( $user ){
        $menu = $this->initMenu();
        $this->user = $user;
        $result = [];
        foreach( $menu as $title => $content ){
            $one = $this->parseMenuItem( $title , $content );
            if( null !== $one ){
                $result[] = $one;
            }
        }
        $this->menu = $result;
        return $result;
    }

    public function parseMenuItem( $title , $content ){

        if( is_callable( $content ) ){
            $content = $content( $this->user , Route::current() );
        }
        if( is_null( $content ) ){
            return null;
        }
        if( ! is_array( $content ) ){
            //仅一级菜单
            $result = $this->createMenuItem( $title , $content , null , null );
        }
        elseif( array_key_exists( '_action' , $content ) ){
            //仅一级菜单 带参数
            if( array_key_exists( '_parameters'  , $content ) ){
                $parameters = $content['_parameters'];
            }
            else{
                $parameters = null;
            }
            $result = $this->createMenuItem( $title , $content['_action'] , $parameters , null );
        }
        else{
            //二级菜单
            $hasActive = false;
            $hasOne = false;
            $submenu = [];
            foreach( $content as $key => $val ){
                $sub = $this->parseMenuItem( $key , $val );
                if( null !== $sub ){
                    if( $sub->active ){
                        $hasActive = true;
                    }
                    $hasOne = true;
                    $submenu[] = $sub;
                }
                if( false == $hasOne ){
                    return null;
                }
            }
            $result = $this->createMenuItem( $title , null , null , $submenu , $hasActive );
        }
        return $result;
    }

    public function createMenuItem( $title , $action , $parameters = null , $sub = null , $active = false){
        $item = new \stdClass();
        $item->title = $title;
        if( null == $action ){
            $item->active = $active;
            $item->url = null;
        }
        elseif( Permission::check( $this->user , $action , $parameters ) ){
            //
            $item->url = URL::action( $action );
            if( Route::current()->getName() == $action ){
                $item->active = true;
            }
            else{
                $item->active = false;
            }
        }
        else{
            //此用户无权限
            return null;
        }
        $item->submenu = $sub;
        return $item;
    }

    public function initMenu(){
        return Config::get( 'laravel-cms::menu_admin' );
        /*
        return array(
            '单个action0' => 'admin.issue.show.index',
            '单个action1' => [
                '_action' => 'admin.issue.show.index',
                '_parameters' => null,
            ],
            '单个action2' => function( $user , $action ){
                    if( $user ){
                        return [
                            '_action' => 'admin.issue.show.index',
                            '_parameters' => null
                        ];
                    }
                    else{
                        return null;
                    }
                },
            '子菜单1' => [
                '1-1' => [
                    '_action' => 'admin.issue.show.index',
                    '_parameters' => null,
                ] ,
                '1-2' => [
                    '_action' => 'admin.issue.show.index',
                    '_parameters' => null,
                ] ,
            ],
            '子菜单2' => [
                '1-1' => 'admin.issue.show.index' ,
                '1-2' => 'admin.issue.show.index' ,
            ]
        );
        */
    }
} 
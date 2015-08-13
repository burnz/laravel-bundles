<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/29
 * Time: 11:50
 */

namespace Xjtuwangke\Admin\Elements;


use Xjtuwangke\Admin\Middleware\AdminAuthMiddleware;
use Xjtuwangke\LaravelModels\UserModel;
use ArrayAccess;
use Iterator;

class KMenu implements ArrayAccess , Iterator{

    /**
     * @var UserModel
     */
    protected $user;

    /**
     * 子菜单
     * @var array
     */
    protected $submenu = array();

    /**
     * @var bool
     */
    protected $valid   = true;

    /**
     * @var string | null
     */
    protected $action;

    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @var int
     */
    protected $cursor = 0;

    /**
     * @var null | string
     */
    protected $url;

    /**
     * @param UserModel $user
     * @param array     $blueprint
     * @return static
     */
    public static function make( UserModel $user , array $blueprint = null ){
        if( is_null( $blueprint ) ){
            $blueprint = \Config::get( 'admin.menu' , array() );
        }
        $menu = new static();
        $child = null;
        foreach( $blueprint as $key => $value ){
            if( is_array( $value ) ){
                //有子目录
                /**
                 * $key = '用户'
                 * $value = array(
                 * '查看' => 'admin::dashboard' ,
                 * '新建' => 'admin::dashboard' ,
                 * '删除' => 'admin::dashboard' ,
                 * )
                 *
                 * $menu->submenu = new Menu( $user , $value )
                 */

                $child = static::make( $user , $value );
            }
            else{
                //无子目录 生成叶子节点
                // $key = '查看'
                // $value = 'admin::dashboard'
                $child = new static;
                $child->setAction( $value );
                if( AdminAuthMiddleware::canAccess( $value , $user ) ){
                    $child->setValid( true );
                }
                else{
                    $child->setValid( false );
                }
            }
            if( $child ){
                $menu[ $key ] = $child;
                if( $child->isActive() ){
                    $menu->setActive();
                }
            }
        }
        return $menu;
    }

    /**
     * @return bool
     */
    public function isLeaf(){
        return empty( $this->submenu );
    }

    /**
     * @return bool
     */
    public function isActive(){
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive( $active = true ){
        $this->active = (bool) $active;
    }


    /**
     * @return bool
     */
    public function isValid(){
        if( $this->isLeaf() ){
            //叶子节点直接根据valid属性判断
            return $this->valid;
        }
        else{
            //空的树节点也是invalid
            $result = false;
            foreach( $this as $submenu ){
                if( $submenu->isValid() ){
                    $result = true;
                }
            }
            return $result;
        }
    }

    /**
     * @param bool $valid
     */
    public function setValid( $valid = true ){
        $this->valid = (bool) $valid;
    }

    /**
     * @return null|string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param null|string $action
     */
    public function setAction($action)
    {
        $this->url = route( $action );
        $this->action = $action;
    }

    public function setURL( $url ){
        $this->url = $url;
    }

    public function getURL(){
        return $this->url;
    }


    /**
     * @inheritdoc
     */
    public function offsetExists($offset){
        return array_key_exists( $offset , $this->submenu );
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset){
        return $this->submenu[ $offset ];
    }


    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value){
        $this->submenu[ $offset ] = $value;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset){
        unset( $this->submenu[ $offset ] );
    }

    /**
     * @inheritdoc
     */
    public function current(){
        $key = $this->key();
        if( is_null( $key ) ){
            return null;
        }
        else{
            return $this->submenu[ $key ];
        }
    }

    /**
     * @inheritdoc
     */
    public function next(){
        $this->cursor = $this->cursor + 1;
    }

    /**
     * @inheritdoc
     */
    public function key(){
        $keys = array_keys( $this->submenu );
        if( array_key_exists( $this->cursor , $keys ) ) {
            return $keys[$this->cursor];
        }
        else{
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function valid(){
        if( is_null( $this->key() ) ){
            return false;
        }
        else{
            return true;
        }
    }

    /**
     * @inheritdoc
     */
    public function rewind(){
        $this->cursor = 0;
    }



}
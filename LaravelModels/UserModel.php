<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/5
 * Time: 21:43
 */

namespace Xjtuwangke\LaravelModels;


class UserModel extends UserModelBase{

    /**
     * @inheritdoc
     */
    public function hasRole($name, $requireAll = false)
    {
        if( parent::hasRole( 'root' ) ){
            return true;
        }
        else{
            return parent::hasRole( $name , $requireAll );
        }
    }

    /**
     * @inheritdoc
     */
    public function can( $permission, $requireAll = false ){
        if( $this->hasRole( 'root' ) ){
            return true;
        }
        else{
            if( parent::can( $permission , $requireAll ) ){
                return true;
            }
            elseif( ! is_array( $permission ) ){
                $permissions = explode( '.' , $permission );
                while( ! empty( $permissions ) ){
                    array_pop( $permissions );
                    $string = implode( '.' , array_merge( $permissions , [ '*' ] ) );
                    if( parent::can( $string ) ){
                        return true;
                    }
                }
                return false;
            }
            else{
                return false;
            }
        }
    }

    /**
     * @return array
     */
    public function getUserRoleNamesArrayAttribute(){
        $array = array();
        $roles = $this->roles;
        foreach( $roles as $one ){
            $array[] = $one->name;
        }
        return $array;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/8
 * Time: 18:29
 */

namespace Xjtuwangke\LaravelModels\Rbac;


use Xjtuwangke\LaravelModels\QueryRequestHandler\QueryRequestHandlerTrait;
use Zizaco\Entrust\EntrustRole;
use Config;

class Role extends EntrustRole{

    protected $table = 'admin_roles';

    protected $dirty_perms = null;

    use QueryRequestHandlerTrait;

    /**
     * @return array
     */
    public function getPermissionsAttribute(){
        $result = array();
        $perms = $this->perms;
        foreach( $perms as $perm ){
            $result[] = $perm->name;
        }
        return $result;
    }

    /**
     * @param array $perms
     */
    public function setPermissionsAttribute( array $perms ){
        $perms = array_unique( $perms , SORT_STRING );
        \DB::beginTransaction();
        $this->save();
        $exists = array();
        foreach( $this->perms as $perm ){
            if( ! in_array( $perm->name , $perms ) ){
                $this->detachPermission( $perm );
            }
            else{
                $exists[] = $perm->name;
            }
        }
        $class = \Config::get('entrust.permission');
        $permissions = array();
        $perms = array_diff( $perms , $exists );
        foreach( $perms as $perm ){
            $permission = $class::where('name' , $perm )->first();
            if( ! $permission ){
                $permission = new $class;
                $permission->name = $perm;
                $permission->save();
            }
            $permissions[] = $permission;
            $exists[] = $permission->name;
        }
        $this->attachPermissions( $permissions );
        \DB::commit();
    }

    /**
     * @param array $perms
     */
    public function batchAddPermissions( array $perms ){
        if( is_null( $this->dirty_perms ) ){
            $this->dirty_perms = $perms;
        }
        else{
            $this->dirty_perms = array_merge( $this->dirty_perms , $perms );
        }
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save( array $options = array() ){
        \DB::beginTransaction();
        if( is_array( $this->dirty_perms ) ){
            $this->setPermissionsAttribute( $this->dirty_perms );
            $this->dirty_perms = null;
        }
        $result = parent::save( $options );
        \DB::commit();
        return $result;
    }
}
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

    public function setPermissionsAttribute( array $perms ){
        \DB::beginTransaction();
        $exists = array();
        foreach( $this->perms as $perm ){
            if( ! in_array( $perm->name , $perms ) ){
                $this->detachPermission( $perm );
            }
            $exists[] = $perm->name;
        }
        $class = \Config::get('entrust.permission');
        $permissions = array();
        foreach( $perms as $perm ){
            if( in_array( $perm , $exists ) ){
                continue;
            }
            $permission = $class::where('name' , $perm )->first();
            if( ! $permission ){
                $permission = new $class;
                $permission->name = $perm;
                $permission->save();
                $permissions[] = $permission;
            }
        }
        $this->attachPermissions( $permissions );
        \DB::commit();
    }
}
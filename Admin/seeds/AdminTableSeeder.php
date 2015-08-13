<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/7/5
 * Time: 01:41
 */

namespace Xjtuwangke\LaravelCms\Seeders;

use Xjtuwangke\LaravelSeeder\BasicTableSeeder;
use Xjtuwangke\LaravelModels\Rbac\RoleModel;
use \AdminUserModel;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends BasicTableSeeder {

    protected $tables = array(
        'Xjtuwangke\LaravelModels\Rbac\RoleModel' ,
        'AdminUserModel' ,
    );

    protected function seeds_admins(){

        $root = RoleModel::getRoot();

        AdminUserModel::createWithRole(
            ['id'=>1 , 'password' => Hash::make('qweasdzxc') , 'email'=>'admin@rollong.com' , 'username'=>'网站后台' , 'mobile'=>'18000000000' , 'employee_id' => '0000' ]
            , $root
        );

        AdminUserModel::createWithRole(
            ['id'=>2 , 'password' => Hash::make('qweasdzxc') , 'email'=>'kwang@rollong.com' , 'username'=>'kwang' , 'mobile'=>'18019101985' , 'employee_id' => '0001' ]
            , $root
        );
        return null;
    }

    protected function seeds_model_RoleModel(){
        RoleModel::create([
            'id' => 1 ,
            'parent_id' => 0,
            'name' => 'root' ,
            'title' => '根管理员' ,
            'desc' => '具有最高权限的管理员' ,
        ])
            ->setPermissions( [ 'admin.*' ] );
        return null;
    }
}
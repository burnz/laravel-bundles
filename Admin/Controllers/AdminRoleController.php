<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/8
 * Time: 15:02
 */

namespace Xjtuwangke\Admin\Controllers;

use Xjtuwangke\Admin\Controllers\CrudControllers\ButtonUtils;
use Xjtuwangke\Admin\Controllers\CrudControllers\CrudController;
use Xjtuwangke\Admin\Elements\KTable\KTable;
use Xjtuwangke\Admin\Elements\KTable\TableHead;
use Xjtuwangke\Admin\FormRequests\RoleControllerFormRequest;
use Xjtuwangke\LaravelModels\Rbac\Role;

class AdminRoleController extends CrudController{

    /**
     * @var string
     */
    protected static $name = '管理员角色';

    /**
     * @var string
     */
    protected static $class = Role::class;

    protected function formRequest(){
        return new RoleControllerFormRequest();
    }

    public function listTable(){
        $table = new KTable();

        $thead = new TableHead( 'name' );
        $thead->setSearchable();
        $thead->setSortable();
        $thead->setHtml( '角色名' );
        $table->addThead( $thead );

        $thead = new TableHead( 'display_name' );
        $thead->setSearchable();
        $thead->setHtml( '角色描述' );
        $table->addThead( $thead );

        $thead = new TableHead( 'action' );
        $thead->setHtml( '操作' );
        $thead->setFunc(function(Role $role){ return ButtonUtils::block_btn_edit( $this , $role );});
        $table->addThead( $thead );

        return $table;
    }

    /**
     * 返回最基本的query
     * @return \Illuminate\Database\Query\Builder;
     */
    public static function queryScope(){
        return Role::where( 'name' , '!=' , 'root' )
            ->where( 'name' , '!=' , 'user' )->where( 'name' , '!=' , 'admin');
    }

}
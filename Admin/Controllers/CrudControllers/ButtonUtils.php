<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/30
 * Time: 18:04
 */

namespace Xjtuwangke\Admin\Controllers\CrudControllers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Xjtuwangke\Admin\Middleware\AdminAuthMiddleware;
use Xjtuwangke\LaravelModels\BaseModel;

class ButtonUtils {

    /**
     * 查看、修改、删除、恢复 四个按钮组合
     * @param CrudController $controller
     * @param Model $item
     * @return string
     */
    public static function block_btn_group( CrudController $controller , Model $item ){
        return static::block_btn_show( $controller , $item )
        . static::block_btn_edit( $controller , $item )
        . static::block_btn_trash( $controller , $item )
        . static::block_btn_restore( $controller , $item );
    }

    /**
     * 生成新增按钮 GET方式跳转新页面
     * @param CrudController $controller
     * @return string
     */
    public static function block_btn_create( CrudController $controller ){
        $url = $controller->redirectToMethodUrl( 'getCreate' , $controller );
        $action = $controller::getActionPrefix() . '.create.form';
        if( AdminAuthMiddleware::canAccess( $action , \Auth::user() ) ){
            $disabled = '';
        }
        else{
            $disabled = 'disabled';
        }
        return "<a class='btn btn-sm btn-info gofarms-btn-actions' href='{$url}' target='_blank' {$disabled}><span class='glyphicon glyphicon-plus'></span></a>";
    }

    /**
     * 生成编辑按钮 GET方式跳转新页面
     * @param CrudController $controller
     * @param Model $item
     * @return string
     */
    public static function block_btn_edit( CrudController $controller , Model $item ){
        if( method_exists( $item , 'trahsed') && true == $item->trashed() ){
            return '';
        }
        $url = $controller->redirectToMethodUrl( 'getEdit' , $controller , [ $item->getKey() ] );
        $action = $controller::getActionPrefix() . '.edit.form';
        if( AdminAuthMiddleware::canAccess( $action , \Auth::user() ) ){
            $disabled = '';
        }
        else{
            $disabled = 'disabled';
        }
        return "<a class='btn btn-sm btn-success gofarms-btn-actions' href='{$url}' {$disabled}><span class='glyphicon glyphicon-pencil'></span></a>";
    }

    /**
     * 生成查看详情按钮 GET方式跳转新页面
     * @param CrudController $controller
     * @param Model $item
     * @return string
     */
    public static function block_btn_show( CrudController $controller , Model $item ){
        $url = $controller->redirectToMethodUrl( 'getShow' , $controller , [ $item->getKey() ] );
        $action = $controller::getActionPrefix() . '.show.detail';
        if( AdminAuthMiddleware::canAccess( $action , \Auth::user() ) ){
            $disabled = '';
        }
        else{
            $disabled = 'disabled';
        }
        return "<a class='btn btn-sm btn-info gofarms-btn-actions' href='{$url}' target='_blank' {$disabled}><span class='glyphicon glyphicon-search'></span></a>";
    }

    /**
     * 生成删除单个资源按钮 DELETE方式跳转新页面
     * @param CrudController $controller
     * @param Model $item
     * @return string
     */
    public static function block_btn_trash( CrudController $controller , Model $item ){
        if( method_exists( $item , 'trahsed') && true == $item->trashed() ){
            return '';
        }
        $url = $controller->redirectToMethodUrl( 'deleteRemove' , $controller  );
        $action = $controller::getActionPrefix() . '.remove.delete';
        if( AdminAuthMiddleware::canAccess( $action , \Auth::user() ) ){
            $disabled = '';
        }
        else{
            $disabled = 'disabled';
        }
        $id = $item->getKey();
        $token = csrf_token();
        $form = <<<FORM
<a class='btn btn-sm btn-danger'
  data-attr-confirm='确定要删除吗'
  data-attr-submit-url='{$url}'
  data-attr-item-id='{$id}'
  data-attr-csrf-token='{$token}'
  data-attr-submit-method='DELETE'
href='javascript:;' onclick='javascript:ktable.submit_form(this);' {$disabled}>
<span class='glyphicon glyphicon-trash'></span>
</a>
FORM;
        return $form;
    }

    /**
     * 移出回收站按钮 POST方式跳转新页面
     * @param CrudController $controller
     * @param Model $item
     * @return string
     */
    public static function block_btn_restore( CrudController $controller , Model $item ){
        if( ! $item instanceof SoftDeletes ){
            return '';
        }
        if( false == $item->trashed() ){
            return '';
        }
        $url = $controller->redirectToMethodUrl( 'postRestore' , $controller  );
        $action = $controller::getActionPrefix() . '.remove.restore';
        if( AdminAuthMiddleware::canAccess( $action , \Auth::user() ) ){
            $disabled = '';
        }
        else{
            $disabled = 'disabled';
        }
        $id = $item->getKey();
        $token = csrf_field();
        $form = <<<FORM
<a class='btn btn-sm btn-danger'
  data-attr-confirm='确定要恢复吗'
  data-attr-submit-url='{$url}'
  data-attr-item-id='{$id}'
  data-attr-csrf-token='{$token}'
href='javascript:;' onclick='javascript:ktable.submit_form(this);' {$disabled}>
<span class='glyphicon glyphicon-repeat'></span>
</a>
FORM;
        return $form;
    }

}
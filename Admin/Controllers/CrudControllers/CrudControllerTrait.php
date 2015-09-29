<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/9/24
 * Time: 17:58
 */

namespace Xjtuwangke\Admin\Controllers\CrudControllers;

use Illuminate\Support\Facades\Input;
use Xjtuwangke\Admin\Elements\KMessenger;
use Xjtuwangke\Admin\Elements\KTable\KTable;
use Xjtuwangke\KForm\DataMapping\SingleEloquentInstance;
use Xjtuwangke\KForm\FormRequest\KFormRequest;
use Xjtuwangke\KForm\SessionFlashedKFormContract;
use Xjtuwangke\QueryRequests\QueryRequest;
use Illuminate\View\View;

trait CrudControllerTrait
{

    /**
     * @return string
     */
    public static function getActionPrefix(){
        return '';
    }

    /**
     * 用QueryRequest修饰Query
     * @param QueryRequest $request
     * @return mixed
     */
    protected function modelQuery( QueryRequest $request ){
        $class = static::$class;
        $query = $class::queryRequestHandler( $request , $this->queryScope() );
        return $query;
    }

    /**
     * 返回最基本的query
     * @return \Illuminate\Database\Query\Builder;
     */
    public static function queryScope(){
        $class = static::$class;
        return $class::whereRaw( ' 1=1 ' );
    }

    public function getLayout(){
        parent::getLayout();
        if( isset( $this->navbar ) ){
            $this->layout->shortcuts = $this->navbar;
        }
    }


    /**
     * 展示items的列表 入口函数
     * @param QueryRequest $request
     * @return string
     */
    public function getIndex( QueryRequest $request )
    {
        $query = $this->modelQuery($request);
        $items = $query->paginate($this->paginate);
        $this->getLayout();
        $this->layout->title = static::$name . '列表';
        $table = $this->listTable();
        $table->itemsToTbody($items);
        $this->layout->content = $table->render($request);
        //为pagination增加原有的请求
        $this->layout->content .= $items->appends($request->all())->render();
        return $this->layout;
    }

    /**
     * @param QueryRequest $request
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function getShow( QueryRequest $request , $id ){
        $item = $this->queryScope()->where( 'id' , $id )->first();
        if( ! $item ){
            abort(404);
        }
        $this->getLayout();
        $this->layout->title   = static::$name . '详情';
        if( method_exists( $this , 'detailsView' ) ){
            $this->layout->content = $this->detailsView( $item , $request );
        }
        else{
            $this->layout->content = $item->toJson();
        }

        return $this->layout;
    }

    /**
     * @return \Xjtuwangke\KForm\KForm
     */
    protected function kfrom(){
        return $this->formRequest()->getKform();
    }

    /**
     * @param $item
     * @return SingleEloquentInstance
     */
    protected function mappingInstance( $item ){
        return new SingleEloquentInstance( $item );
    }

    /**
     * 保存items信息
     * @param KFormRequest $request
     * @param null         $id
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handlePostStore( KFormRequest $request , $id = null ){
        \DB::beginTransaction();
        $form = $request->getKform();
        if( $id ){
            $instance = $this->queryScope()->find( $id );
            if( ! $instance ){
                abort( 404 );
            }
        }
        else{
            $instance = new static::$class;
        }
        $form->addMappingInstance( $this->mappingInstance( $instance ) );
        $form->mapToInstance();
        $instance->save();
        \DB::commit();
        $messenger = new KMessenger();
        if( $id ){
            $messenger->push( '成功修改' . static::$name , KMessenger::SUCCESS);
        }
        else{
            $messenger->push( '成功新增' . static::$name , KMessenger::SUCCESS);
        }
        return $this->redirectToMethod( 'getIndex' );
    }

    /**
     * 显示编辑表格
     * @param SessionFlashedKFormContract $form
     * @param null | int                  $id
     * @return View
     */
    public function getEdit( SessionFlashedKFormContract $form = null , $id ){
        $item = null;
        if( ! $form ){
            $form = $this->kfrom();
        }
        $title = '编辑' . static::$name;
        $item = $this->queryScope()->where( 'id' , $id )->first();
        if( ! $item ){
            abort( 404 );
        }
        $form->addMappingInstance( $this->mappingInstance( $item ) );
        $form->mapFromInstance();
        $this->getLayout();
        $this->layout->title  = $title;
        $this->layout->content = $form->render();
        return $this->layout;
    }

    /**
     * 显示新建表格
     * @param SessionFlashedKFormContract $form
     * @return View
     */
    public function getCreate( SessionFlashedKFormContract $form = null ){
        $item = null;
        if( ! $form ){
            $form = $this->kfrom();
        }
        $title = '新建' . static::$name;
        $this->getLayout();
        $this->layout->title  = $title;
        $this->layout->content = $form->render();
        return $this->layout;
    }

    /**
     * 处理删除请求
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteRemove(){
        $id = Input::get( 'id' );
        $result = false;
        if( $id ){
            if( ! is_array( $id ) ){
                $id = [ $id ];
            }
            if( ! empty( $id ) ){
                $result = $this->queryScope()->whereIn( 'id' , $id )->delete();
            }
        }
        $messenger = new KMessenger();
        if( $result ){
            $messenger->push( '成功删除了一个' . static::$name , KMessenger::SUCCESS );
        }
        else{
            $messenger->push( '删除' . static::$name . '失败' , KMessenger::ERROR );
        }
        return $this->redirectToMethod( 'getTrashed' );
    }

    /**
     * 处理恢复请求
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRestore(){
        $id = Input::get( 'id' );
        $result = false;
        if( $id ){
            if( ! is_array( $id ) ){
                $id = [ $id ];
            }
            if( ! empty( $id ) ){
                $result = $this->queryScope()->onlyTrashed()->whereIn( 'id' , $id )->restore();
            }
        }
        $messenger = new KMessenger();
        if( $result ){
            $messenger->push( '成功恢复了一个' . static::$name , KMessenger::SUCCESS );
        }
        else{
            $messenger->push( '恢复' . static::$name . '失败' , KMessenger::ERROR );
        }
        return $this->redirectToMethod( 'getIndex' );
    }

    /**
     * 已删除的items列表
     * @param QueryRequest $request
     * @return \Illuminate\Contracts\View\View
     */
    public function getTrashed( QueryRequest $request ){
        $query = $this->modelQuery( $request )->onlyTrashed();
        $items = $query->paginate( $this->paginate );
        $this->getLayout();
        $this->layout->title   = static::$name . '列表';
        $table = $this->listTable();
        $table->itemsToTbody( $items );
        $this->layout->content = $table->render( $request );
        $this->layout->content.= $items->appends( $request->all()  )->render();
        return $this->layout;
    }
}
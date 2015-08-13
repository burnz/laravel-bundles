<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 00:29
 */

namespace Xjtuwangke\Admin\Elements\KTable;


use \HTML;
use Xjtuwangke\QueryRequests\QueryRequest;

class KTable {

    protected $thead = [];

    protected $tbody = [];

    protected $tr = [];

    protected $attributes = array(
        'class' => 'table table-hover table-bordered' ,
        'data-role' => 'table'
    );

    protected $current_sort_status = [];

    protected $current_search_keywords = [];

    protected $current_group_status = [];

    protected $queries = [];

    protected $queryFunc = null;

    protected $title = '';

    protected $tail = '';

    public function __construct(){
        $this->attributes = HTML::attributes( $this->attributes );
    }

    /**
     * 设定表格的html属性
     * @param array $attributes
     * @return $this
     */
    public function attributes( $attributes = [] ){
        $this->attributes = HTML::attributes( $attributes );
        return $this;
    }

    /**
     * 得到某个特定的thead
     * @param $name
     * @return null | $thead
     */
    public function getThead( $name ){
        if( array_key_exists( $name , $this->thead ) ){
            return $this->thead[ $name ];
        }
        else{
            return null;
        }
    }

    /**
     * @param TableHead $thead
     * @return $this
     */
    public function addThead( TableHead $thead ){
        $this->thead[ $thead->getField() ] = $thead;
        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function removeThead( $field ){
        if( array_key_exists( $field , $this->thead ) ){
            unset( $this->thead[$field] );
        }
        return $this;
    }

    /**
     * 获得所有的Thead
     * @return array
     */
    public function getTheads(){
        return $this->thead;
    }

    /**
     * 标题区域
     * @param $title
     * @return $this
     */
    public function setTitle( $title ){
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * 设置单个td的值
     * @param       $html
     * @param array $attributes
     * @param bool  $xss
     * @return $this
     */
    public function td( $html , $attributes = [] , $xss = true ){
        $td = new \stdClass();
        if( false == $xss ){
            $td->html = $html;
        }
        else{
            $td->html = e( $html );
        }

        $td->attributes = HTML::attributes( $attributes );
        $this->tr[] = $td;
        return $this;
    }

    /**
     * 表格换行
     * @param array $attributes
     * @return $this
     */
    public function tr( $attributes = [] ){
        $tr = new \stdClass();
        $tr->attributes = HTML::attributes( $attributes );
        $tr->children = $this->tr;
        $this->tbody[] = $tr;
        $this->tr = [];
        return $this;
    }

    public function setTail( $html = '' ){
        $this->tail = $html;
        return $this;
    }

    public function getTail(){
        return $this->tail;
    }

    public function getAttributes(){
        return $this->attributes;
    }

    public function getTbody(){
        return $this->tbody;
    }

    public function itemsToTbody( $items ){
        if( null == $items ){
            return;
        }
        foreach( $items as $item ){
            foreach( $this->thead as $th ){
                $func = $th->getFunc();
                if( is_callable( $func ) ){
                    $this->td( $func( $item ) , [] , false );
                }
                else{
                    $this->td( $item->{$th->getField()} , [] , true );
                }
            }
            $this->tr();
        }
    }

    public function render( QueryRequest $request ){
        return TableDrawer::render( $this , $request );
    }
}
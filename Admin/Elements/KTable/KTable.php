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

    /**
     * @var null | \Closure
     */
    protected $trAttributes = null;

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

    /**
     * @param string $html
     * @return $this
     */
    public function setTail( $html = '' ){
        $this->tail = $html;
        return $this;
    }

    /**
     * @return string
     */
    public function getTail(){
        return $this->tail;
    }

    /**
     * @return array|string
     */
    public function getAttributes(){
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getTbody(){
        return $this->tbody;
    }

    /**
     * @param $items
     * @param \Closure|null $trAttributes
     */
    public function itemsToTbody( $items , \Closure $trAttributes = null ){
        if( null == $items ){
            return;
        }
        if( is_null( $trAttributes ) ){
            $trAttributes = $this->trAttributes;
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
            if( is_null( $trAttributes ) ){
                $this->tr();
            }
            else{
                $this->tr( $trAttributes( $item ) );
            }
        }
    }

    /**
     * @param \Closure $trAttributes
     * @return $this
     */
    public function setTrAttributesFunc( \Closure $trAttributes ){
        $this->trAttributes = $trAttributes;
        return $this;
    }

    /**
     * @param QueryRequest $request
     * @return string
     */
    public function render( QueryRequest $request ){
        return TableDrawer::render( $this , $request );
    }
}
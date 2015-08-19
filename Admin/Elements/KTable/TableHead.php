<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 00:30
 */

namespace Xjtuwangke\Admin\Elements\KTable;

use Xjtuwangke\QueryRequests\Contracts\OrderQueryContract;
use Xjtuwangke\QueryRequests\QueryRequest;

class TableHead {

    protected $html = '';

    protected $attributes;

    protected $field;

    protected $searchable;

    protected $groupable;

    protected $sortable;

    protected $width;

    protected $func;

    public function __construct( $field ){
        $this->field = $field;
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param $html
     * @return TableHead
     */
    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @param $html
     * @return TableHead
     */
    public function appendHtml( $html ){
        $this->html.= $html;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param $attributes
     * @return TableHead
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    /**
     * @param bool $searchable
     * @return $this
     */
    public function setSearchable($searchable = true)
    {
        $this->searchable = $searchable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroupable()
    {
        return $this->groupable;
    }

    /**
     * @param array $groupable
     * @param bool|true $hasAll
     * @return $this
     */
    public function setGroupable($groupable = array() , $hasAll = true )
    {
        if( $hasAll ){
            $groupable = array_merge( ['*'=>'全部'] , $groupable );
        }
        $this->groupable = $groupable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortable()
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     * @return $this
     */
    public function setSortable($sortable = true )
    {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param $width
     * @return TableHead
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFunc()
    {
        return $this->func;
    }

    /**
     * @param callable $func
     * @return TableHead
     */
    public function setFunc( callable $func )
    {
        $this->func = $func;
        return $this;
    }

    /**
     * 生成th的html
     * @param QueryRequest $request
     * @return string
     */
    public function render( QueryRequest $request ){
        $field = $this->getField();
        if( true == $this->getSearchable() ){
            $keyword = $request->getSearchValue( $field );
            $search = <<<SEARCHING
<div class="row">
    <div class="input-group input-sm" style="width:150px;margin:0 auto;">
        <input class="form-control input-sm input-table-search" value="{$keyword}" name="search[$field]" type="text" placeholder="关键词"
        onfocus="$(this).parents("div.input-group").css('width',200);" onblur="$(this).parents("div.input-group").css('width',150)"
        >
        <div class="input-group-addon input-sm table-search-btn" onclick="javascript:$(this).parents('form').submit();"><span class="glyphicon glyphicon-search"></span></div>
        <div class="input-group-addon input-sm table-reset-search-btn" onclick="javascript:$(this).siblings('input').val('');$(this).parents('form').submit();"><span class="glyphicon glyphicon-remove"></span></div>
    </div>
</div>
SEARCHING;
            $this->appendHtml( $search );
        }

        if( $this->getSortable() ){
            $orderValue = $request->getOrderValue( $field );
            $asc = $orderValue == OrderQueryContract::ORDER_ASC?'btn-success':'btn-default';
            $desc = $orderValue == OrderQueryContract::ORDER_DESC?'btn-success':'btn-default';
            $order_btn = <<<BUTTONS
<div class="btn-group btn-group-xs table-sort-btn" attr-field="{$field}">
    <input name="order[{$field}]" value="{$orderValue}" style="display:none;"/>
    <button attr-btn-sort="asc" type="button" class="btn {$asc}" onclick="javascript:$(this).siblings('input').val('asc');$(this).parents('form').submit();">
        <span class="glyphicon glyphicon-arrow-down"></span>
    </button>
    <button attr-btn-sort="desc" type="button" class="btn {$desc}" onclick="javascript:$(this).siblings('input').val('desc');$(this).parents('form').submit();">
        <span class="glyphicon glyphicon-arrow-up"></span>
    </button>
</div>
BUTTONS;
            $this->appendHtml( $order_btn );
        }

        if( $this->getGroupable() ){
            $options = $this->getGroupable();
            $dropdown = <<<LI
LI;
            $first = null;
            foreach( $options as $key => $val ){
                if( null === $first ){
                    $first = $val;
                }
                $dropdown.= <<<LI
<li onclick="javascript:$(this).parents('ul.dropdown-menu').find('input').val('{$key}');$(this).parents('form').submit();">
<a class='btn-table-groupby' attr-groupby='{$key}'>{$val}</a>
</li>
LI;
;
            }
            $current = $request->getEqualsValue( $field );
            if( '*' == $current ){
                $optionsValue = '全部';
            }
            elseif( ! array_key_exists( $current , $options ) ){
                $current = '*';
                $optionsValue = '全部';
            }
            else{
                $optionsValue = $options[ $current ];
            }
            $group_btn = <<<GROUP
<div class="row">
    <div class="btn-group" style="margin:0 auto;">
      <button type="button" class="btn btn-xs btn-success">{$optionsValue}</button>
      <button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <input name="equals[{$field}]" value="{$current}" style="display:none;"/>
        {$dropdown}
      </ul>
    </div>
</div>
GROUP;
            $this->appendHtml( $group_btn );
        }
        $attributes = $this->getAttributes();
        $html       = $this->getHtml();
        return "<th {$attributes}>{$html}</th>\n";
    }
}
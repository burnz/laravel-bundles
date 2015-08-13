<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 02:22
 */

namespace Xjtuwangke\QueryRequests;


use Illuminate\Foundation\Http\FormRequest;
use Xjtuwangke\QueryRequests\Contracts\EqualsQueryContract;
use Xjtuwangke\QueryRequests\Contracts\OrderQueryContract;
use Xjtuwangke\QueryRequests\Contracts\PageQueryContract;
use Xjtuwangke\QueryRequests\Contracts\SearchQueryContract;
use Xjtuwangke\QueryRequests\Contracts\WhereInQueryContract;

/**
 * 解析Table发过来的过滤请求，包括
 *     Search
 *     Order
 *     Group
 *     Page
 * Class QueryRequest
 * @package Xjtuwangke\Admin\Elements\KTable
 */
class QueryRequest extends FormRequest implements SearchQueryContract , EqualsQueryContract ,
    OrderQueryContract , PageQueryContract , WhereInQueryContract{

    /**
     * @return bool
     */
    public function passesAuthorization(){
        return true;
    }

    /**
     * dummy function
     */
    public function validate(){

    }

    protected $search_query;

    /**
     * 解析Search请求
     * search[name]=patten
     * @return array
     */
    public function getSearchQuery(){
        if( is_null( $this->search_query ) ){
            $this->search_query = $this->input( 'search' , array() );
            foreach( $this->search_query as $key => $value ){
                $this->search_query[ $key ] = @(string) $value;
            }
        }
        return $this->search_query;
    }

    /**
     * 获取某个field的搜索关键字
     * @param $field
     * @return null | string
     */
    public function getSearchValue( $field ){
        $array = $this->getSearchQuery();
        if( array_key_exists( $field , $array ) && is_string( $array[$field] ) ){
            return $array[ $field ];
        }
        else{
            return null;
        }
    }

    protected $equals_query;

    /**
     * 解析equals请求
     * equals[name1]=1&equals[name2]=2
     * @return array
     */
    public function getEqualsQuery(){
        if( is_null( $this->equals_query ) ){
            $this->equals_query = $this->input( 'equals' , array() );
            foreach( $this->equals_query as $key => $value ){
                $this->equals_query[ $key ] = @(string) $value;
            }
        }
        return $this->equals_query;
    }

    /**
     * 获取某个field的equals关键字
     * @param $field
     * @return null|string
     */
    public function getEqualsValue( $field ){
        $array = $this->getEqualsQuery();
        if( array_key_exists( $field , $array ) && is_string( $array[ $field ]) ){
            return $array[ $field ];
        }
        else{
            return null;
        }
    }

    protected $order_query = null;

    /**
     * 解析排序请求
     * order[name1]=asc|desc&order[name2]=asc|desc
     * @return array
     */
    public function getOrderQuery(){
        if( is_null( $this->order_query ) ){
            $this->order_query = $this->input( 'order' , array() );
            foreach( $this->order_query as $key => $value ){
                $value = strtolower( $value );
                if( ! in_array( $value , [ OrderQueryContract::ORDER_ASC , OrderQueryContract::ORDER_DESC]) ){
                    unset( $this->order_query[ $key ] );
                }
                else{
                    $this->order_query[ $key ] = $value;
                }
            }
        }
        return $this->order_query;
    }

    /**
     * 获取某个field的order关键字
     * @param $field
     * @return null|string
     */
    public function getOrderValue( $field ){
        $array = $this->getOrderQuery();
        if( array_key_exists( $field , $array ) ){
            return (string) $array[ $field ];
        }
        else{
            return null;
        }
    }


    /**
     * 解析分页请求
     * @return int
     */
    public function getQueriedPage(){
        return $this->input( 'page' , 1 );
    }

    protected $wherein_query = null;

    /**
     * 解析Wherein请求
     * wherein[name][]=patten&wherein[name][]=patten
     * @return array
     */
    public function getWhereInQuery(){
        if( is_null( $this->wherein_query ) ){
            $array = $this->input( 'wherein' , array() );
            foreach( $array as $key => $value ){
                if( ! is_array( $value ) ){
                    $value = [ $value ];
                }
                $newValue = array();
                foreach( $value as $one ){
                    $newValue[] = @ ( string ) $one;
                }
                $array[ $key ] = $newValue;
            }
            $this->wherein_query = $array;
        }
        return $this->wherein_query;
    }

    /**
     * 获取某个field的wherein关键字
     * @param $field
     * @return null | array
     */
    public function getWhereInValue( $field ){
        $array = $this->getWhereInQuery();
        if( array_key_exists( $field , $array ) ){
            return $array[ $field ];
        }
        else{
            return null;
        }
    }
}
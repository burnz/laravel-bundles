<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 19:34
 */

namespace Xjtuwangke\LaravelModels\Demo;


use Xjtuwangke\LaravelModels\BaseModel;

class DemoModel extends BaseModel{

    protected $table = 'demo_table';

    /**
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public static function handleEqualsQuery( $query , $field , $value ){
        if( in_array( $field , [ 'title' , 'book' , 'name' ] ) ){
            return parent::handleEqualsQuery( $query , $field , $value );
        }
        else{
            return $query;
        }
    }

    /**
     * @param $query
     * @param $field
     * @param $order
     * @return mixed
     */
    public static function handleOrderQuery( $query , $field , $order ){
        if( in_array( $field , [ 'title' , 'book' , 'name' ] ) ){
            return parent::handleOrderQuery( $query , $field , $order );
        }
        else{
            return $query;
        }
    }

    /**
     * @param       $query
     * @param       $field
     * @param array $wherein
     */
    public static function handleWhereInQuery( $query , $field , array $wherein ){
        if( in_array( $field , [ 'title' , 'book' , 'name' ] ) ){
            return parent::handleWhereInQuery( $query , $field , $wherein );
        }
        else{
            return $query;
        }
    }

    /**
     * @param       $query
     * @param       $field
     * @param array $patterns
     * @return mixed
     */
    public static function handleSearchQuery( $query , $field , array $patterns ){
        if( in_array( $field , [ 'title' , 'book', 'name' ] ) ){
            return parent::handleSearchQuery( $query , $field , $patterns );
        }
        else{
            return $query;
        }
    }
}
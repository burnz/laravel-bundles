<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 19:17
 */

namespace Xjtuwangke\LaravelModels\QueryRequestHandler;


use Illuminate\Http\Request;
use Xjtuwangke\QueryRequests\Contracts\EqualsQueryContract;
use Xjtuwangke\QueryRequests\Contracts\OrderQueryContract;
use Xjtuwangke\QueryRequests\Contracts\SearchQueryContract;
use Xjtuwangke\QueryRequests\Contracts\WhereInQueryContract;

trait QueryRequestHandlerTrait {

    /**
     * @param Request $request
     * @param         $query
     * @return mixed
     */
    public static function queryRequestHandler( Request $request , $query ){
        if( $request instanceof SearchQueryContract ){
            $query = static::getSearchQuery( $query , $request->getSearchQuery() );
        }
        if( $request instanceof EqualsQueryContract ){
            foreach( $request->getEqualsQuery() as $field => $value ){
                $fieldName  = @(string) $field;
                $fieldValue = @(string) $value;
                $query = static::handleEqualsQuery( $query , $fieldName , $fieldValue );
            }
        }
        if( $request instanceof OrderQueryContract ){
            $hasOrder = false;
            foreach( $request->getOrderQuery() as $field => $value ){
                $hasOrder = true;
                $fieldName  = @(string) $field;
                $fieldValue = @(string) $value;
                $query = static::handleOrderQuery( $query , $fieldName , $fieldValue );
            }
            if( ! $hasOrder ){
                $query->orderBy( 'created_at' , 'desc' );
            }
        }
        if( $request instanceof WhereInQueryContract ){
            foreach( $request->getWhereInQuery() as $field => $value ){
                $fieldName = @(string) $field;
                $query = static::handleWhereInQuery( $query , $fieldName , $value );
            }
        }
        return $query;
    }

    /**
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public static function handleEqualsQuery( $query , $field , $value ){
        if( '*' === $value ){
            return $query;
        }
        else{
            return $query->where( $field , $value );
        }
    }

    /**
     * @param $query
     * @param $field
     * @param $order
     * @return mixed
     */
    public static function handleOrderQuery( $query , $field , $order ){
        switch( $order ){
            case OrderQueryContract::ORDER_ASC :
                $query = $query->orderBy( $field , 'asc' );
                break;
            case OrderQueryContract::ORDER_DESC :
                $query = $query->orderBy( $field , 'desc' );
                break;
        }
        return $query;
    }

    /**
     * @param       $query
     * @param       $field
     * @param array $wherein
     */
    public static function handleWhereInQuery( $query , $field , array $wherein ){
        if( empty( $wherein ) ){
            return $query->whereRaw( '1=0' );
        }
        else{
            return $query->wherein( $field , $wherein );
        }
    }

    /**
     * 根据patterns修饰searchQuery
     * @param       $query
     * @param array $patterns
     * @return mixed
     */
    public static function getSearchQuery( $query , array $patterns){
        foreach( $patterns as $field => $pattern ){
            $pattern = @(string) $pattern;
            $query = static::handleSearchQuery( $query , $field , static::explodePattern( $pattern ) );
        }
        return $query;
    }

    /**
     * 处理单条search请求
     * @param       $query
     * @param       $field
     * @param array $patterns
     * @return mixed
     */
    public static function handleSearchQuery( $query , $field , array $patterns ){
        foreach( $patterns as $pattern ){
            if( $pattern ){
                $query = $query->where( $field , 'like' , "%{$pattern}%" );
            }
        }
        return $query;
    }

    /**
     * @param $pattern
     * @return array
     */
    public static function explodePattern( $pattern ){
        return preg_split( '/\s+/' , $pattern );
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 14:56
 */

namespace Xjtuwangke\QueryRequests\Contracts;


interface OrderQueryContract {

    const ORDER_ASC  = 'asc';

    const ORDER_DESC = 'desc';

    /**
     * 解析排序请求
     * order[name1]=asc|desc&order[name2]=asc|desc
     * @return array
     */
    public function getOrderQuery();

    /**
     * 获取某个field的order关键字
     * @param $field
     * @return null|string
     */
    public function getOrderValue( $field );
}
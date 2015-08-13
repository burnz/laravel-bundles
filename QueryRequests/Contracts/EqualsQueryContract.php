<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 18:51
 */

namespace Xjtuwangke\QueryRequests\Contracts;


interface EqualsQueryContract {


    /**
     * 解析equals请求
     * equals[name1]=1&equals[name2]=2
     * @return array
     */
    public function getEqualsQuery();

    /**
     * 获取某个field的equals关键字
     * @param $field
     * @return null|string
     */
    public function getEqualsValue( $field );
}
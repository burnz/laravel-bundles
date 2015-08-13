<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 18:53
 */

namespace Xjtuwangke\QueryRequests\Contracts;

interface WhereInQueryContract {

    /**
     * 解析Wherein请求
     * wherein[name][]=patten&wherein[name][]=patten
     * @return array
     */
    public function getWhereInQuery();

    /**
     * 获取某个field的wherein关键字
     * @param $field
     * @return null | array
     */
    public function getWhereInValue( $field );
}
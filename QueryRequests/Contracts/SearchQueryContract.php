<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 14:34
 */

namespace Xjtuwangke\QueryRequests\Contracts;

interface SearchQueryContract {

    /**
     * 解析Search请求
     * search[name]=patten
     * @return array
     */
    public function getSearchQuery();

    /**
     * 获取某个field的搜索关键字
     * @param $field
     * @return null | string
     */
    public function getSearchValue( $field );
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 14:59
 */

namespace Xjtuwangke\QueryRequests\Contracts;


interface PageQueryContract {

    /**
     * 解析分页请求
     * @return int
     */
    public function getQueriedPage();


}
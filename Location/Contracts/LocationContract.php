<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 18:58
 */

namespace Xjtuwangke\Location\Contracts;

interface LocationContract {

    /**
     * 获取经度
     * @return float | null
     */
    public function getLongitude();

    /**
     * 获取纬度
     * @return float | null
     */
    public function getLatitude();

    /**
     * 定位经度
     * @return float | null
     */
    public function getPrecision();

    /**
     * 距离(米)
     * @param LocationContract $location
     * @return float
     */
    public function getDistance( LocationContract $location );

    /**
     * 标题
     * @return string | null
     */
    public function getTitle();

    /**
     * @return AreaContract
     */
    public function getArea();

}
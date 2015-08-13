<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 19:20
 */

namespace Xjtuwangke\Location\Concretes;


use Xjtuwangke\Location\Contracts\LocationContract;
use Xjtuwangke\Location\Contracts\AreaContract;

class Location implements LocationContract{

    protected $longitude;

    protected $latitude;

    protected $precision;

    protected $title;

    /**
     * @param        $long
     * @param        $lat
     * @param string $title
     * @param null   $precision
     */
    public function __construct( $long , $lat , $title = 'here' , $precision = null ){
        $this->longitude = is_null($long)?:(float) $long;
        $this->latitude  = is_null($lat)?:(float) $lat;
        $this->title     = is_null($title)?:(string)$title;
        $this->precision = is_null($precision)?:(float)$precision;
    }

    /**
     * 获取经度
     * @return float | null
     */
    public function getLongitude(){
        return $this->longitude;
    }

    /**
     * 获取纬度
     * @return float | null
     */
    public function getLatitude(){
        return $this->latitude;
    }

    /**
     * 定位经度
     * @return float | null
     */
    public function getPrecision(){
        return $this->precision;
    }

    /**
     * 距离(米)
     * @param LocationContract $location
     * @return float
     */
    public function getDistance( LocationContract $location ){
        //将角度转为狐度
        $radLat1 = deg2rad($this->getLatitude());
        $radLat2 = deg2rad($location->getLatitude());
        $radLng1 = deg2rad($this->getLongitude());
        $radLng2 = deg2rad($location->getLongitude());
        $a = $radLat1 - $radLat2; //两纬度之差,纬度<90
        $b = $radLng1 - $radLng2; //两经度之差纬度<180
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378137.0;
        return $s;
    }

    /**
     * 标题
     * @return string | null
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * @return AreaContract | null
     */
    public function getArea(){
        return null;
    }
}
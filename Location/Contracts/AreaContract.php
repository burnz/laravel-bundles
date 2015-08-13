<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 19:12
 */

namespace Xjtuwangke\Location\Contracts;

use DateTimeZone;

interface AreaContract {

    const Continent_Asia = 'Asia';
    const Continent_Africa = 'Africa';
    const Continent_South_America = 'South America';
    const Continent_North_America = 'North America';
    const Continent_Europe = 'Europe';
    const Continent_Oceania = 'Oceania';
    const Continent_Antarctica = 'Antarctica';

    const Area_Continent = 1;
    const Area_Country   = 2;
    const Area_State     = 3;
    const Area_City      = 4;
    const Area_Distinct  = 5;
    const Area_Street    = 6;

    /**
     * 获得所在的州
     * @return string
     */
    public function getContinent();

    /**
     * 获得所在的国家
     * @return string
     */
    public function getCountry();

    /**
     * 获得所在的省
     * @return string
     */
    public function getState();

    /**
     * 获得所在的城市
     * @return mixed
     */
    public function getCity();

    /**
     * 获得所在的区、县
     * @return mixed
     */
    public function getDistinct();

    /**
     * 获得所在的街道
     * @return mixed
     */
    public function getStreet();

    /**
     * @param $address
     * @return mixed
     */
    public function setAddress( $address );

    /**
     * @return string
     */
    public function getFullAddress();

    /**
     * 获取当前邮编
     * @return string
     */
    public function getZipCode();

    /**
     * 获取当前时区
     * @return DateTimeZone;
     */
    public function getTimezone();

    /**
     * 获取当地语言
     * @return string
     */
    public function getLang();

    /**
     * @return mixed
     */
    public function getCurrentAreaLevel();

    /**
     * @return array
     */
    public function getChildren();

    /**
     * @return AreaContract | null
     */
    public function getParent();
}
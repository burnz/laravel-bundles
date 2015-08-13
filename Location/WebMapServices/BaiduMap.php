<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 19:26
 */

namespace Xjtuwangke\Location\WebMapServices;


use Xjtuwangke\Location\Contracts\LocationContract;
use Xjtuwangke\Location\Contracts\WebMapServiceContract;

class BaiduMap implements WebMapServiceContract{

    /**
     * @param LocationContract $location
     * @param int              $target
     * @return string
     */
    public static function url( LocationContract $location , $target = WebMapServiceContract::Target_PC ){
        $title = urlencode( $location->getTitle() );
        $lat = $location->getLatitude();
        $lng = $location->getLongitude();
        return "http://api.map.baidu.com/marker?location=$lat,$lng&title=" . $title . "&output=html&src=rollong|projects";
    }
}
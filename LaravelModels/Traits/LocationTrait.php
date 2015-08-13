<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 18:54
 */

namespace Xjtuwangke\LaravelModels\Traits;


use Illuminate\Database\Schema\Blueprint;

use Xjtuwangke\Location\Contracts\LocationContract;
use Xjtuwangke\Location\Concretes\Location;

trait LocationTrait {

    static public function _schema_locationTrait( Blueprint $table ){
        $table->string( 'longitude' )->nullable();
        $table->string( 'latitude' )->nullable();
        $table->string( 'location_precision' )->nullable();
        $table->string( 'location_title' )->nullable();
        return $table;
    }

    /**
     * @param LocationContract $location
     * @return $this
     */
    public function setLocation( LocationContract $location ){
        $this->longitude = $location->getLongitude();
        $this->latitude  = $location->getLatitude();
        $this->location_precision = $location->getPrecision();
        $this->location_title = $location->getTitle();
        $this->save();
        return $this;
    }

    /**
     * @return LocationContract
     */
    public function getLocation(){
        return new Location( $this->longitude , $this->latitude , $this->location_title , $this->location_precision );
    }

    /**
     * 获取与另一个LocationTrait的距离(米)
     * @param LocationTrait $that
     * @return float
     */
    public function getDistance( LocationTrait $that ) {
        $here = $this->getLocation();
        $there = $that->getLocation();
        return $here->getDistance( $there );
    }

}
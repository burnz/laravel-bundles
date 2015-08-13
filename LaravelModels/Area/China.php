<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/6
 * Time: 12:44
 */

namespace Xjtuwangke\LaravelModels\Area;


use Illuminate\Database\Schema\Blueprint;
use Xjtuwangke\LaravelModels\BaseModel;
use Xjtuwangke\Location\Contracts\AreaContract;

class China extends BaseModel implements  AreaContract{

    protected $table = 'area_china';

    protected $municipalities = [ '北京市' , '天津市' , '上海市' , '重庆市' ];

    /**
     * @param Blueprint $table
     * @return Blueprint
     */
    public static function _schema_chinaArea( Blueprint $table ){
        $table->integer( 'areano' );
        $table->integer( 'parentno' );
        $table->string( 'name' );
        $table->string( 'province' )->nullable();
        $table->string( 'city' )->nuallable();
        $table->string( 'distinct' )->nullable();
        $table->enum( 'flag' , [ '国家' , '省' , '市' , '区/县' ] );
        $table->integer( 'sort' )->default( 99 );
        $table->integer( 'zipcode' )->default( 0 );
        return $table;
    }

    /**
     * @param $areano
     * @param $parentno
     * @param $areaname
     * @return static
     */
    public static function insertArea( $areano , $parentno , $areaname ){
        $parent = static::findByAreano( $parentno );
        $attributes = array(
            'areano' => $areano ,
            'parentno' => $parentno ,
            'name' => $areaname ,
        );
        switch( $parent->getCurrentAreaLevel() ){
            case AreaContract::Area_Country:
                $attributes[ 'flag' ] = '省';
                $attributes['province'] = $areaname;
                break;
            case AreaContract::Area_State:
                $attributes[ 'flag' ] = '市';
                $attributes['province'] = $parent->province;
                $attributes['city']     = $areaname;
                break;
            case AreaContract::Area_City:
                $attributes[ 'flag' ] = '区/县';
                $attributes['province'] = $parent->province;
                $attributes['city'] = $parent->city;
                $attributes['distinct']     = $areaname;
                break;
        }
        return static::create( $attributes );
    }

    /**
     * @return China
     */
    public static function getRoot(){
        return static::findByAreano( '100000' );
    }

    /**
     * @param $areano string
     * @return China
     */
    public static function findByAreano( $areano ){
        return static::where( 'areano' , $areano )->first();
    }

    /**
     * 获得所在的大州
     * @return string
     */
    public function getContinent(){
        return AreaContract::Continent_Asia;
    }

    /**
     * 获得所在的国家
     * @return string
     */
    public function getCountry(){
        return 'China';
    }

    /**
     * 获得所在的省
     * @return string
     */
    public function getState(){
        return $this->province;
    }

    /**
     * 获得所在的城市
     * @return mixed
     */
    public function getCity(){
        return $this->city;
    }

    /**
     * 获得所在的区、县
     * @return mixed
     */
    public function getDistinct(){
        return $this->distinct;
    }

    /**
     * 获得所在的街道
     * @return mixed
     */
    public function getStreet(){
        return null;
    }

    /**
     * @param $address
     * @return mixed
     */
    public function setAddress( $address ){
        return null;
    }

    /**
     * @return string
     */
    public function getFullAddress(){
        return $this->getState() . $this->getCity() . $this->getDistinct();
    }

    /**
     * 获取当前邮编
     * @return string
     */
    public function getZipCode(){
        return $this->zipcode;
    }

    /**
     * 获取当前时区
     * @return \DateTimeZone;
     */
    public function getTimezone(){
        return new \DateTimeZone('Asia/Shanghai');
    }

    /**
     * 获取当地语言
     * @return string
     */
    public function getLang(){
        return 'zh-cn';
    }

    /**
     * @return mixed
     */
    public function getCurrentAreaLevel(){
        switch( $this->flag ){
            case '国家':
                return AreaContract::Area_Country;
            case '省':
                return AreaContract::Area_State;
            case '市':
                return AreaContract::Area_City;
            case '区/县':
                return AreaContract::Area_Distinct;
            default:
                return AreaContract::Area_Distinct;
        }
    }

    /**
     * @inheritdoc
     */
    public function getChildren(){
        return static::where( 'parentno' , $this->areano )->get();
    }

    /**
     * @inheritdoc
     */
    public function getParent(){
        return static::where( 'areano' , $this->parentno )->first();
    }
}
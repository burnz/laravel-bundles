<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/6
 * Time: 14:41
 */

namespace Xjtuwangke\L5Controller;


use Xjtuwangke\LaravelModels\Area\China;
use Xjtuwangke\Location\Contracts\AreaContract;

class L5ChinaAreaController extends L5Controller{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChildren(){
        $areano = \Input::get( 'areano' );
        $results = array();
        if( ! $areano || ! $parent = China::findByAreano( $areano ) ){
            $results[ 'error' ] = '错误的areano';
        }
        else{
            $results['children'] = array();
            foreach( $parent->getChildren() as $child ){
                $results['children'][ $child->areano ] = $child->name;
            }
        }
        return \Response::json( $results , 200 , array() , JSON_UNESCAPED_UNICODE );
    }

    public function getAll(){
        $results = array();
        $root = China::getRoot();
        foreach( $root->getChildren() as $province ){
            $results[ $province->areano ] = array(
                'name' => $province->name ,
                'children' => array() ,
            );
            foreach( $province->getChildren() as $city ){
                $results[ $province->areano ]['children'][ $city->areano ] = array(
                    'name' => $city->name ,
                    'children' => array() ,
                );
                foreach( $city->getChildren() as $distinct ){
                    $results[ $province->areano ]['children'][ $city->areano ]['children'][ $distinct->areano ] = array(
                        'name' => $distinct->name ,
                    );
                }
            }
        }
        return \Response::json( $results , 200 , array() , JSON_UNESCAPED_UNICODE );
    }

    public function getDetail(){
        $areano = \Input::get( 'areano' , 100000 );
        $results = array();
        if( ! $area = China::findByAreano( $areano ) ){
            $results[ 'error' ] = '错误的areano';
        }
        else{
            $results['provinces'] = array();
            $results['cities']    = array();
            $results['distrincts'] = array();
            $children = array();
            foreach( $area->getChildren() as $child ){
                $children[ $child->areano ] = $child->name;
            }
            foreach( China::where( 'flag' , AreaContract::Area_State )->get() as $province ){
                $results['provinces'][ $province->areano ] = $province->name;
            }
            switch( $area->getCurrentAreaLevel() ){
                case AreaContract::Area_Country:
                    break;
                case AreaContract::Area_State:
                    $results['current']['province'] = $area->province;
                    $results['cities'] = $children;
                    break;
                case AreaContract::Area_City:
                    $cities = array();
                    foreach( $area->getParent()->getChildren() as $child ){
                        $cities[$child->areano] = $child->name;
                    }
                    $results['current']['province'] = $area->province;
                    $results['current']['city']     = $area->city;
                    $results['cities'] = $cities;
                    break;
                case AreaContract::Area_Distinct:
                    $cities = array();
                    foreach( $area->getParent()->getParent()->getChildren() as $child ){
                        $cities[$child->areano] = $child->name;
                    }
                    $distincts = array();
                    foreach( $area->getParent()->getChildren() as $child ){
                        $distincts[$child->areano] = $child->name;
                    }
                    $results['current']['province'] = $area->province;
                    $results['current']['city']     = $area->city;
                    $results['current']['distinct'] = $area->name;
                    $results['cities'] = $cities;
                    $results['distincts'] = $distincts;
                    break;
                default:
                    break;
            }
        }
        return \Response::json( $results , 200 , array() , JSON_UNESCAPED_UNICODE );
    }

    public function getDsy(){
        $root = China::getRoot();
        $results = $this->dsyAddChild( '0' , $root );
        return implode( "\n" , $results );
    }

    protected function dsyAddChild( $prefix , China $parent ){
        $results = array();
        //dsy.add("0",["北京市","天津市","上海市","重庆市","河北省","山西省","内蒙古","辽宁省","吉林省","黑龙江省","江苏省","浙江省","安徽省","福建省","江西省","山东省","河南省","湖北省","湖南省","广东省","广西","海南省","四川省","贵州省","云南省","西藏","陕西省","甘肃省","青海省","宁夏","新疆","香港","澳门","台湾省"]);
        $children = array();
        foreach( $parent->getChildren() as $child ){
            $children[] = '"' . $child->name . '"';
        }
        if( empty( $children ) ){
            return array();
        }
        $results[] = sprintf( 'dsy.add("%s",[%s]);' , $prefix , implode( ',' , $children ) );
        $i = 0;
        foreach( $parent->getChildren() as $child ){
            $results = array_merge( $results , $this->dsyAddChild( $prefix . '_' . $i , $child ) );
            $i++;
        }
        return $results;
    }

    public function getTest(){
        return \HTML::script( url('js/area.js') ) . static::renderAreaSelection();
    }

    public static function renderAreaSelection(){
        $html = <<<HTML
<div class="area-selection">
<select id="s_province">
  <option value="00">省份</option>
</select>
<select id="s_city">
  <option value="00">地级市</option>
</select>
<select id="s_county">
  <option value="00">区、县</option>
</select>
<input name="areano" style="display:none;">
</div>
<script>
var s=["s_province","s_city","s_county"];//三个select的name
  var opt0 = ["省份","地级市","市、县级市"];//初始值
  for (i = 0; i < s.length - 1; i++) {
        document.getElementById(s[i]).onchange = new Function("change(" + (i + 1) + ")");
    }
  change(0);
</script>
HTML;
        return $html;
    }
}
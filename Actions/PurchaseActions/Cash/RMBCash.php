<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 23:15
 */

namespace Xjtuwangke\Actions\PurchaseActions\Cash;


class RMBCash extends CentBasedCash implements CashContract{

    /**
     * 返回现金的单位
     * @return string
     */
    public function getUnit(){
        return '元';
    }

    /**
     * @return string
     */
    public static function getType(){
        return 'RMB';
    }

}
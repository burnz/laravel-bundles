<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/26
 * Time: 02:46
 */

namespace Xjtuwangke\Actions\PurchaseActions\Entities;

use Xjtuwangke\Actions\PurchaseActions\Cash\CashContract;

class Account {

    protected $cashes = array();

    /**
     * @param $type
     * @return null | CashContract
     */
    public function getCash( $type ){
        if( in_array( $type , $this->cashes ) ){
            return $this->cashes[ $type ];
        }
        else{
            return null;
        }
    }

    public function setCash( $type ){

    }
}
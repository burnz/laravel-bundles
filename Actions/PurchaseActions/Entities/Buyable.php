<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/26
 * Time: 02:40
 */

namespace Xjtuwangke\Actions\PurchaseActions\Entities;


interface Buyable {

    /**
     * @return mixed
     */
    public function getPrice();
}
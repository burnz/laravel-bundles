<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/11
 * Time: 00:12
 */

namespace Xjtuwangke\Cart;

/**
 * Interface CartItemInterface
 * @package Xjtuwangke\Cart
 * @deprecated
 */
interface CartItemInterface{

    public function isChecked();

    public function check( $bool = true );

    public function gerPrice();

    public function getFingerPrint();

}
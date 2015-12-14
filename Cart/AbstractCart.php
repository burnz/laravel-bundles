<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/11
 * Time: 00:11
 */

namespace Xjtuwangke\Cart;

/**
 * Class AbstractCart
 * @package Xjtuwangke\Cart
 * @deprecated
 */
abstract class AbstractCart{

    protected $items = array();

    /**
     * @param CartItemInterface $item
     * @param $count
     */
    public function add( CartItemInterface $item , $count ){
        $key = $item->getFingerPrint();
        if( $this->has( $item ) ){
            $this->items[$key]['count']+= $count;
        }
        else{
            $this->items[$key] = array(
                'item' => $item ,
                'count' => $count ,
                'checked' => true ,
            );
        }
    }

    /**
     * @param CartItemInterface $item
     * @return bool
     */
    public function check( CartItemInterface $item ){
        $key = $item->getFingerPrint();
        if( $this->has( $item ) ){
            $this->items[$key]['checked']+= true;
            return true;
        }
        return false;
    }

    /**
     * @param CartItemInterface $item
     * @return bool
     */
    public function has( CartItemInterface $item ){
        $key = $item->getFingerPrint();
        return array_key_exists( $key , $this->items );
    }

    /**
     * @param CartItemInterface $item
     */
    public function remove( CartItemInterface $item ){
        $key = $item->getFingerPrint();
        if( $this->has( $item ) ){
            unset( $this->items[ $key ] );
        }
    }
}
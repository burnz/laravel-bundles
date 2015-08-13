<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/26
 * Time: 02:20
 */

namespace Xjtuwangke\Actions\PurchaseActions\Actions;


use Xjtuwangke\Actions\AbstractAction\AbstractAction;
use Xjtuwangke\Actions\PurchaseActions\Roles\Buyer;

class PurchaseAction extends AbstractAction{

    /**
     * @param Buyer $creator
     * @return static
     */
    public static function create( Buyer $creator ){
        return new static( $creator );
    }

    public function tryCommit(){
    }

    public function getContext(){
    }

}
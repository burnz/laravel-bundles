<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/17
 * Time: 14:24
 */

namespace Xjtuwangke\Contracts\WithMessageBag;


use Illuminate\Support\MessageBag;

trait WithMessageBagTrait {

    /**
     * @var MessageBag
     */
    protected $_messageBag = null;

    /**
     * @return MessageBag
     */
    public function getMessageBag(){
        if( is_null( $this->_messageBag ) ){
            $this->_messageBag = new MessageBag();
        }
        return $this->_messageBag;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/17
 * Time: 14:21
 */

namespace Xjtuwangke\Contracts\WithMessageBag;

use Illuminate\Contracts\Support\MessageBag;


interface WithMessageBag {

    /**
     * @return MessageBag
     */
    public function getMessageBag();

}
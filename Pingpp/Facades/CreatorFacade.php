<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/10/13
 * Time: 11:50
 */

namespace Xjtuwangke\Pingpp\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

class CreatorFacade extends  BaseFacade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor(){
        return 'pingpp.charge.create';
    }
}
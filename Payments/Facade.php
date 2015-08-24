<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/23
 * Time: 18:34
 */

namespace Xjtuwangke\Payments;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor(){
        return 'payments.factory';
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/20
 * Time: 15:21
 */

namespace Xjtuwangke\Aliyun\SLS;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor(){
        return 'aliyun.sls';
    }
}
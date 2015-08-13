<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 17:28
 */

namespace Xjtuwangke\KForm\Facades;


use Illuminate\Support\Facades\Facade;

class KFormFactoryFacade extends Facade{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'kformfieldfactory'; }
}
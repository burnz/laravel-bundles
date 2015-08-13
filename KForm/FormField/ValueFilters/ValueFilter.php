<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 17:22
 */

namespace Xjtuwangke\KForm\FormField\ValueFilters;


abstract class ValueFilter {

    abstract public function filter( $input );
}
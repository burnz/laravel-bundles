<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 17:39
 */

namespace Xjtuwangke\KForm\FormField\ValueFilters;


class XSSValueFilter extends ValueFilter{

    public function filter( $value ){
        return htmlentities( $value );
    }
}
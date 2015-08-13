<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 17:23
 */

namespace Xjtuwangke\KForm\FormField\ValueFilters;


class TrimValueFilter extends ValueFilter{

    /**
     * @param $input
     * @return string
     */
    public function filter( $input ){
        return trim( $input );
    }
}
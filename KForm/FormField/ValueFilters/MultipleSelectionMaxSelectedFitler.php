<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 21:15
 */

namespace Xjtuwangke\KForm\FormField\ValueFilters;

class MultipleSelectionMaxSelectedFitler extends ValueFilter{

    protected $max_selected = null;

    /**
     * @param $max
     */
    public function __construct( $max ){
        $this->max_selected = (int) $max > 0?:null;
    }

    /**
     * @param $input
     * @return array
     */
    public function filter( $input ){
        if( !is_array( $input ) ){
            $input = [ $input ];
        }
        if( ! is_null( $this->max_selected ) ){
            return array_slice( $input , 0 , $this->max_selected );
        }
        else{
            return $input;
        }
    }

}
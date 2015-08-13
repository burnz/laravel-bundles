<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 16:00
 */

namespace Xjtuwangke\KForm\Traits;


use Xjtuwangke\KForm\FormField\FormField;

trait FormRowTrait {

    /**
     * @var array
     */
    protected $rows = array();

    /**
     * @var array
     */
    protected $current_row = array();

    /**
     * @var float
     */
    protected $current_row_used = 0.0;

    /**
     * @param FormField $field
     * @return $this
     */
    public function addToCurrentRow( FormField $field , $index ){
        if( ! $this->willExceedCurrentRow( $field ) ){
            $this->newRow();

        }
        $this->current_row[] = $index;
        $this->current_row_used+= $field->getWidth();
        return $this;
    }

    /**
     * @return $this
     */
    public function newRow(){
        $this->rows[] = $this->current_row;
        $this->current_row = array();
        $this->current_row_used = 0.0;
        return $this;
    }

    /**
     * @param FormField $field
     * @return bool
     */
    public function willExceedCurrentRow( FormField $field ){
        if( $this->current_row_used + $field->getWidth() > 1.0 ){
            return false;
        }
        else{
            return true;
        }
    }
}
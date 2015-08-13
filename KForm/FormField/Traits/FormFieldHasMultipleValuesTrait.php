<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 21:12
 */

namespace Xjtuwangke\KForm\FormField\Traits;


use Xjtuwangke\KForm\FormField\ValueFilters\MultipleSelectionMaxSelectedFitler;

trait FormFieldHasMultipleValuesTrait {

    /**
     * 多选框 最大的选择个数
     * @var int
     */
    protected $max_selected = null;

    /**
     * @param $value
     * @return $this
     */
    public function setMaxSelected( $value ){
        $this->max_selected = (int) $value >= 0?:null;
        $this->addValueFilter( new MultipleSelectionMaxSelectedFitler( $this->max_selected ) );
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxSelected(){
        return $this->max_selected;
    }
}
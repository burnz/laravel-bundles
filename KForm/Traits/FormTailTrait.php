<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 14:22
 */

namespace Xjtuwangke\KForm\Traits;


trait FormTailTrait {

    /**
     * @var string
     */
    protected $tail = <<<HTML
<div class="row"><button type="submit" class="btn btn-primary">确定</button></div>
HTML;

    /**
     * @param $tail
     * @return $this
     */
    public function setTail( $tail ){
        $this->tail = ( string ) $tail;
        return $this;
    }

    /**
     * @return string
     */
    public function getTail(){
        return $this->tail;
    }


}
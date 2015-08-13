<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/4
 * Time: 12:32
 */

namespace Xjtuwangke\BugSnag;


use Xjtuwangke\Contracts\WithMessageBag\WithMessageBagTrait;
use Xjtuwangke\Contracts\WithMessageBag\WithMessageBag;

class Exception extends \Exception implements  WithMessageBag{

    use WithMessageBagTrait;

    /**
     * @var bool
     */
    protected $report = true;

    /**
     * @param $key
     * @param $value
     */
    public function setExceptionMetaData( $key , $value ){
        $this->getMessageBag()->add( $key , $value );
    }

    /**
     * @return array
     */
    public function getExceptionMetaData(){
        return $this->getMessageBag()->toArray();
    }

    /**
     * @param null $bool
     * @return $this
     */
    public function setWillBeReported( $bool = null ){
        $this->report = ( true == $bool );
        return $this;
    }

    /**
     * @return bool
     */
    public function willBeReported(){
        return $this->report;
    }

}
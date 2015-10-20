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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class Exception extends \Exception implements  WithMessageBag{

    use WithMessageBagTrait;

    /**
     * @var bool
     */
    protected $report = true;

    /**
     * @var null|string
     */
    protected $_notice = null;

    /**
     * @var array
     */
    protected $_debug = array();

    /**
     * @var null | \Exception
     */
    protected $_toReport = null;

    /**
     * 设置异常的meta信息
     * @param $key
     * @param $value
     */
    public function setExceptionMetaData( $key , $value ){
        $this->getMessageBag()->add( $key , $value );
    }

    /**
     * 获取异常的meta信息
     * @return array
     */
    public function getExceptionMetaData(){
        return $this->getMessageBag()->toArray();
    }

    /**
     * 是否报告此异常
     * @param null $bool
     * @return $this
     */
    public function setWillBeReported( $bool = null ){
        $this->report = ( true == $bool );
        return $this;
    }

    /**
     * @param \Exception $e
     * @return $this
     */
    public function wantReport( \Exception $e ){
        $this->_toReport = $e;
        $this->setWillBeReported( true );
        return $this;
    }

    /**
     * @return $this|\Exception
     */
    public function getWillReport(){
        if( ! $this->_toReport ){
            return $this;
        }
        else{
            return $this->_toReport;
        }
    }

    /**
     * @return bool
     */
    public function willBeReported(){
        return $this->report;
    }

    /**
     * @return string
     */
    public function getNotice(){
        if( $this->_notice ){
            return $this->_notice;
        }
        else{
            return $this->getMessage();
        }
    }

    /**
     * @param string $notice
     */
    public function setNotice( $notice ){
        $this->_notice = $notice;
    }

    /**
     * @param array $debug
     */
    public function setDebug( array $debug ){
        $this->_debug = $debug;
    }

    /**
     * @return array
     */
    public function getDebug(){
        return $this->_debug;
    }

    /**
     * @return array
     */
    public function toArray(){
        return array(
            'message' => $this->getMessage() ,
            'notice'  => $this->getNotice() ,
            'debug'   => $this->getDebug() ,
            'meta'    => $this->getExceptionMetaData() ,
        );
    }

    /**
     * @return Response | null
     */
    public function getHtmlResponse(){
        return null;
    }

    /**
     *  @return JsonResponse | null
     */
    public function getJsonResponse(){
        return null;
    }

}
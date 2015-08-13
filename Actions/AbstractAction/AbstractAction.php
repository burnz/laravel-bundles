<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/26
 * Time: 01:40
 */

namespace Xjtuwangke\Actions\AbstractAction;


use Xjtuwangke\Actions\AbstractAction\Roles\Creator;
use Xjtuwangke\Actions\AbstractAction\Roles\Listener;

class AbstractAction implements ActionContract{

    /**
     * @var Creator
     */
    protected $creator;

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * @var int
     */
    protected $status = ActionContract::Trans_Uncommitted;

    /**
     * @param Creator $creator
     */
    public function __construct( Creator $creator ){
        $this->creator = $creator;
    }

    /**
     * 获取Creator
     * @param Creator $creator
     * @return static
     */
    public static function create( Creator $creator ){
        return new static( $creator );
    }

    /**
     * @inheritdoc
     */
    public function getCreator(){
        return $this->creator;
    }

    /**
     * @inheritdoc
     */
    public function addListener( Listener $listener ){
        $this->listeners[] = $listener;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function rollback(){
        $this->fireEvent( 'onRollback' , [ $this ] );
    }

    /**
     * @inheritdoc
     */
    public function commit(){
        $this->fireEvent( 'onCommit' , [ $this ] );
        try{
            $this->tryCommit();
        }
        catch( \Exception $e ){
            $this->rollback();
            $this->status = ActionContract::Trans_Failed;
            $this->fireEvent( 'onFail' , [ $this , $e ] );
            throw $e;
        }
        $this->status = ActionContract::Trans_Succeed;
        $this->fireEvent( 'onSuccess' , [ $this ] );
    }

    /**
     * 遍历Listeners执行监听程序
     * @param       $event
     * @param array $parameters
     */
    protected function fireEvent( $event , $parameters = array() ){
        foreach( $this->listeners as $listener ){
            call_user_func_array( [ $listener , $event ] , $parameters );
        }
    }

    /**
     * @return void
     */
    protected function tryCommit(){}

    /**
     * @return int
     */
    public function getResult(){
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function getContext(){ return array();}

    /**
     * @inheritdoc
     */
    public function name(){
        $class = get_class( $this );
        return $class;
    }


}
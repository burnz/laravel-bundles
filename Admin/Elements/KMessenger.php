<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 23:50
 */

namespace Xjtuwangke\Admin\Elements;

use Session;

/**
 * Message队列 再下一次页面中展示处理结果
 * Class KMessenger
 * @package Xjtuwangke\Admin\Elements
 */
class KMessenger {

    const NOTICE = 1;
    const WARNING = 2;
    const SUCCESS = 3;
    const ERROR = 4;

    const ALERT = 1;
    const DIALOG = 2;

    protected $max_records = 10;

    /**
     * @var string
     */
    const sess_key = '_custom_message_flashdata';

    /**
     * @return array
     */
    protected function getMessage(){
        return Session::get( static::sess_key , array() );
    }

    protected function setMessage( array $message ){
        Session::set( static::sess_key , $message );
    }

    /**
     * @param $text
     * @param null $type
     */
    public function push( $text , $type = null ){
        $message = $this->getMessage();
        $message[] = array( 'text' => $text , 'type' => $type );
        while( count( $message ) > $this->max_records ){
            array_pop( $message );
        }
        $this->setMessage( $message );
    }

    /**
     * 清除旧信息
     */
    public function clear(){
        Session::forget( static::sess_key );
    }

    /**
     * @return string
     */
    public function show(){
        $html = '';
        $message = $this->getMessage();
        foreach( $message as $piece ){
            $html.= $this->showOnePiece( $piece['text'] , $piece['type'] );
        }
        $this->clear();
        return $html;
    }

    /**
     * @param $text
     * @param $type
     * @return string
     */
    public function showOnePiece( $text , $type ){
        switch( $type ){
            case static::NOTICE:
                $class = 'alert-info';
                break;
            case static::WARNING:
                $class = 'alert-warning';
                break;
            case static::SUCCESS:
                $class = 'alert-success';
                break;
            case static::ERROR:
                $class = 'alert-danger';
                break;
            default:
                $class = 'alert-info';
                break;
        }
        $html = <<<HTML
<div class="alert {$class} alert-dismissible fade in" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
  {$text}
</div>
HTML;
        return $html;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 18:47
 */

namespace Xjtuwangke\L5Controller;

use Illuminate\Contracts\View\View;
use Xjtuwangke\L5Controller\Assets\Assets;

abstract class L5ViewController extends L5Controller{

    /**
     * @var View
     */
    protected $layout;

    protected $theme  = 'default';

    /**
     * @var null | Assets
     */
    protected $assets  = null;

    /**
     * @return View
     */
    abstract protected function getLayout();

    /**
     * @param $view
     * @return View
     */
    public function makeView( $view ){
        return view( $view )->with( '_controller' , $this );
    }

    /**
     * @return Assets
     */
    public function getAssets(){
        if( is_null( $this->assets ) ){
            $this->assets = new Assets();
        }
        return $this->assets;
    }

    /**
     * @param       $method
     * @param null  $class
     * @param array $parameters
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToMethod( $method , $class = null , $parameters = array() ){
        return redirect()->action( $this->fullActionName( $method , $class ) , $parameters );
    }

    /**
     * @param       $method
     * @param null  $class
     * @param array $parameters
     * @return string
     */
    public function redirectToMethodUrl( $method , $class = null , $parameters = array() ){
        return $this->redirectToMethod( $method , $class , $parameters )->getTargetUrl();
    }

    /**
     * @param      $method
     * @param null $class
     * @return string
     */
    public function fullActionName( $method , $class = null ){
        if( is_null( $class ) ){
            $class = $this;
        }
        return '\\' . get_class( $class ) . '@' . $method;
    }

}
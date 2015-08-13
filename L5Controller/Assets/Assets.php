<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 18:55
 */

namespace Xjtuwangke\L5Controller\Assets;


/**
 * Class Assets
 * @package Xjtuwangke\L5Controller\Assets
 */
class Assets {

    /**
     * @var array
     */
    protected $js = array();

    /**
     * @var array
     */
    protected $css = array();

    /**
     * @var string
     */
    protected $version = '0';

    /**
     * @var null
     */
    protected $path    = null;

    /**
     * @return string
     */
    public function getVersion(){
        return $this->version;
    }

    /**
     * @param $js
     * @return $this
     */
    public function appendJS( $js ){
        if( ! in_array( $js , $this->js ) ){
            $this->js[] = $js;
        }
        return $this;
    }

    /**
     * @param $css
     * @return $this
     */
    public function appendCSS( $css ){
        if( ! in_array( $css , $this->css ) ){
            $this->css[] = $css;
        }
        return $this;
    }

    /**
     * @param $path
     * @return string
     */
    public function getFullPath( $path ){
        return url( $this->path . '/' . $path );
    }

    /**
     * @param $js
     * @return string
     */
    public function renderJS( $js ){
        if( ! $this->isFullUrl( $js ) ){
            $js = $this->getFullPath( $js );
        }
        return \HTML::script( $js );
    }

    /**
     * @param $css
     * @return string
     */
    public function renderCSS( $css ){
        if( ! $this->isFullUrl( $css ) ){
            $css = $this->getFullPath( $css );
        }
        return \HTML::style( $css );
    }

    /**
     * @param $url
     * @return bool
     */
    public function isFullUrl( $url ){
        if( preg_match('/^http\:\/\/.*/i' , $url ) ){
            return true;
        }
        if( preg_match('/^https\:\/\/.*/i' , $url ) ){
            return true;
        }
        if( preg_match('/^\/\/.*/i' , $url ) ){
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function renderAll(){
        $all = array();
        foreach( $this->css as $css ){
            $all[] = $this->renderCSS( $css );
        }
        foreach( $this->js as $js ){
            $all[] = $this->renderJS( $js );
        }
        return implode( "" , $all );
    }

    /**
     * @param $uri string
     * @return string
     */
    public function getImageUrl( $uri ){
        if( $this->isFullUrl( $uri ) ){
            return $uri;
        }
        else{
            return $this->getFullPath( $uri );
        }
    }

    /**
     * @return string
     */
    public function __toString(){
        return $this->renderAll();
    }
}
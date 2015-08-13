<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 17:00
 */

namespace Xjtuwangke\KForm\FormField\Traits;


trait FormFieldLabelTrait {

    protected $label = '';

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel( $label ){
        if( is_string( $label ) ){
            $this->label = $label;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(){
        return $this->label;
    }

    public function addTooltip( $tooltip , $message ){
        $this->label.= static::tooltip( $tooltip , $message );
        return $this;
    }

    /**
     * 基于bootstrap3显示tooltip
     * @param $message
     * @param $icon
     * @return string
     */
    static function tooltip( $icon , $message ){
        $message = str_replace( "\"" , "&quot;" , $message );
        $html = <<<HTML
<span class="glyphicon glyphicon-{$icon}" style="margin-left: 8px " data-toggle="tooltip" data-placement="top" title="{$message}"></span>
HTML;
        return $html;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 23:48
 */

namespace Xjtuwangke\Admin\Elements;


use \HTML;

/**
 * Class KPanel
 */
class KPanel
{

    protected $title = '';

    protected $content = '';

    protected $attributes = [];

    protected $list_group = [];

    protected $btn = 'btn-primary';

    protected $collapse = false;

    const STYLE_PRIMARY = 'primary';
    const STYLE_SUCCESS = 'success';
    const STYLE_INFO = 'info';
    const STYLE_WARNING = 'warning';
    const STYLE_DANGER = 'danger';


    public function __construct($type = self::STYLE_PRIMARY)
    {
        $this->btn = 'btn-' . $type;
        return $this->type('box-' . $type);
    }

    public function title($title = '', $xss = true)
    {
        if (false !== $xss) {
            $this->title = e($title);
        } else {
            $this->title = $title;
        }
        return $this;
    }

    public function content($content = '', $xss = false)
    {
        if (true == $xss) {
            $this->content = e($content);
        } else {
            $this->content = $content;
        }
        return $this;
    }

    public function type($type)
    {
        $this->attributes['class'] = 'box box-solid ' . $type;
        return $this;
    }

    public function collapse( $bool = true )
    {
        $this->collapse = $bool;
    }

    public function draw()
    {
        if ($this->collapse) {
            $this->attributes['class'] .= ' collapsed-box';
        }
        $attributes = HTML::attributes($this->attributes);
        $collapsed = $this->collapse?'none':'block';
        $html = <<<BOX
<div {$attributes}>
<div class="box-header">
<h3 class="box-title">{$this->title}</h3>
<div class="box-tools pull-right">
<button class="btn {$this->btn} btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
<!--
<button class="btn {$this->btn} btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
-->
</div>
</div>
<div class="box-body" style="display:{$collapsed};">
{$this->content}
</div><!-- /.box-body -->
</div>
BOX;

        return $html;
    }

    public function __toString()
    {
        return $this->draw();
    }
}
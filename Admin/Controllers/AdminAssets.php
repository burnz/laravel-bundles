<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 19:20
 */

namespace Xjtuwangke\Admin\Controllers;


use Xjtuwangke\L5Controller\Assets\Assets;

class AdminAssets extends Assets{

    protected $version = '1.0';

    protected $path    = 'xjtuwangke-admin';

    /**
     * 预加载的css文件
     * @var array
     */
    protected $css = array(
        'css/basic.min.css' ,
        'css/admin_lte.min.css' ,
        'css/admin.css' ,
        'uploadifive/uploadifive.css'
    );

    /**
     * 预加载的js文件
     * @var array
     */
    protected $js = array(
        'js/basic.min.js' ,
        'js/admin.min.js' ,
        'js/admin_lte.min.js' ,
        'ckeditor/ckeditor.js' ,
        'ckeditor/config.js' ,
        'ckeditor/styles.js' ,
        'ckeditor/lang/zh-cn.js' ,
        'js/admin_custom.min.js' ,
        'uploadifive/jquery.uploadifive.min.js' ,
        'js/form.js' ,
        '../js/area.js' ,
    );

    public function getFullPath( $path ){
        return url( $this->path . '/' . $path );
    }

}
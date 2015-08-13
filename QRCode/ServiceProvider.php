<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/26
 * Time: 01:56
 */

namespace Xjtuwangke\QRCode;

use Illuminate\Support\ServiceProvider as AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider{

    public function boot(){
        $this->publishes(array(
            __DIR__.'/config/' => config_path(),
        ));
        $this->app->singleton('qrcode.factory' , function(){
            return new QRFactory();
        });
    }

    public function register(){

    }

}
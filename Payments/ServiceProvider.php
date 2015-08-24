<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/23
 * Time: 18:33
 */

namespace Xjtuwangke\Payments;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Xjtuwangke\Payments\Factory;

class ServiceProvider extends BaseServiceProvider
{
    protected $defer = true;

    public function boot(){
        $this->publishes(array(
            __DIR__ . '/config' => config_path('payments/') ,
        ));

    }

    public function register(){
        $this->app->singleton(['payments.factory'=>Factory::class],function(){
            return new Factory();
        });
    }

    public function provides(){
        return array( 'payments.factory' );
    }

}
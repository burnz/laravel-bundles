<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 18:15
 */

namespace Xjtuwangke\KForm\ServiceProvider;


use Illuminate\Support\ServiceProvider;

class KFormViewServiceProvider extends ServiceProvider{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     *
     */
    public function boot(){
        $this->loadViewsFrom(__DIR__.'/../views', 'xjtuwangke-kform');
        $this->publishes(array(
            __DIR__.'/../config/' => config_path('kforms/'),
        ));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(){
    }
}
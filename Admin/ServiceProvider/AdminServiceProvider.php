<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 18:37
 */

namespace Xjtuwangke\Admin\ServiceProvider;


use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Auth;


class AdminServiceProvider extends RouteServiceProvider{

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = '\Xjtuwangke\Admin\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //
        parent::boot($router);

        $this->loadViewsFrom(__DIR__.'/../views', 'xjtuwangke-admin');

        /*
        \Auth::extend( 'xjtuwangke-admin' , function( Container $app ){
            $model = $app['config']['admin.auth.model'];
            return new EloquentUserProvider($app['hash'], $model);
        });
        */

        $this->publishes(array(
            __DIR__.'/../config/' => config_path('admin/'),
        ));

        $this->publishes(array(
            __DIR__.'/../migrations/' => database_path('/migrations')
        ));
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $attributes = array(
            'namespace' => $this->namespace ,
            'as'        => 'admin::' ,
            'prefix'    => 'admin' ,
        );
        $router->group( $attributes , function ($router) {
            require 'routes.php';
        });
    }

    /**
     * @return \Illuminate\Auth\Guard
     */
    public static function getAuthDriver(){
        return Auth::driver();
    }
}
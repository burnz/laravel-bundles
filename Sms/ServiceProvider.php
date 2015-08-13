<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/12
 * Time: 06:20
 */

namespace Xjtuwangke\Sms;

use Xjtuwangke\BugSnag\Exception;

class ServiceProvider extends \Illuminate\Support\ServiceProvider{

    public function register(){
        $this->app->singleton(['sms.sender' => SenderContract::class ],function($app){
            $config = $app['config']['sms'];
            if( ! $config || ! is_array( $config ) || ! array_key_exists( 'sender' , $config ) ){
                throw new Exception("SMS服务未进行配置");
            }
            $sender = $config['sender'];
            return SenderFactory::make( $sender , $config , $app['httpClient'] , $app['cache.store'] , $app['log'] );
        });
        $this->publishes(array(
            __DIR__.'/config/' => config_path(''),
        ));
    }

    public function boot(){
    }

}
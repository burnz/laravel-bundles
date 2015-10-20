<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/10/12
 * Time: 22:28
 */

namespace Xjtuwangke\Pingpp;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Xjtuwangke\Pingpp\Requests\Create;
use Pingpp\Pingpp;
use Xjtuwangke\Pingpp\Exceptions\PingppException;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     *
     */
    public function boot(){
        $this->publishes(array(
            __DIR__ . '/config' => config_path('payments/') ,
            __DIR__ . '/cert'   => storage_path('cert/pingpp/'),
            __DIR__ . '/js'     => public_path('assets/pingpp/'),
        ));
    }

    /**
     *
     */
    public function register(){
        $this->app->singleton(['pingpp.charge.create'=> Create::class],function(){
            list( $sk , $pk , $config ) = $this->readConfig();
            Pingpp::setApiKey( $sk );
            $creator = new Create();
            $creator->setConfig( $config );
            return $creator;
        });
    }

    /**
     * @return array
     * @throws PingppException
     */
    protected function readConfig(){
        $sk =  config('payments.pingpp.sk');
        if( ! $sk ){
            throw new PingppException('Ping++ SK missing');
        }
        $pk =  config('payments.pingpp.sk');
        if( ! $pk ){
            throw new PingppException('Ping++ PK missing');
        }
        $config = config('payments.pingpp.charge' , array() );
        if( ! array_get( $config , 'app.id' ) ){
            throw new PingppException('Ping++ APP ID missing');
        }
        return array( $sk , $pk , $config );
    }

    /**
     * @return array
     */
    public function provides(){
        return array(
            'pingpp.charge.create' , Create::class ,
        );
    }
}
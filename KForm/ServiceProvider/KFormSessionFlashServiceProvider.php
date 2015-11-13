<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 17:00
 */

namespace Xjtuwangke\KForm\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use Xjtuwangke\KForm\Dumper\FormDumpCommand;
use Xjtuwangke\KForm\KDummyForm;
use Xjtuwangke\KForm\KForm;
use Xjtuwangke\KForm\KFormFactory;
use Xjtuwangke\KForm\SessionFlashedKFormContract;

class KFormSessionFlashServiceProvider extends ServiceProvider{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(){
        $this->app->singleton( 'kformfieldfactory' , function(){
            return new KFormFactory();
        });
        //从Session flashdata中读取缓存的KForm 便于依赖注入
        $this->app->bind( SessionFlashedKFormContract::class , function(){
            $instance = $this->app['session.store']->get( KForm::$session_flash_key );
            if( $instance instanceof KForm ){
                return $instance;
            }
            else{
                return new KDummyForm();
            }
        });

        $this->app['command.form.dump'] = $this->app->share(
            function ($app) {
                $config = $this->app['config']['kforms.dumper'];
                if( ! $config ){
                    $config = array();
                }
                return new FormDumpCommand( $config );
            }
        );

        $this->commands('command.form.dump');
    }

    /**
     * @return array
     */
    public function provides(){
        return array( SessionFlashedKFormContract::class , 'command.form.dump' );
    }
}
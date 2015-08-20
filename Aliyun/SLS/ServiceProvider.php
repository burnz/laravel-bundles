<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/20
 * Time: 15:13
 */

namespace Xjtuwangke\Aliyun\SLS;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/config/aliyun-sls.php';
        $this->publishes([
            $configPath => config_path('aliyun-sls.php')
        ]);
        $this->mergeConfigFrom($configPath, 'aliyun-sls');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('aliyun.sls', function ($app) {
            $config = $app->config->get('aliyun-sls');
            return new AliyunSLSAdapter($config['endpoint'], $config['access_key_id'], $config['access_key'], $config['project'], $config['logstore']);
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'aliyun.sls'
        );
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/13
 * Time: 11:41
 */

namespace Xjtuwangke\DebugInfo;

use Barryvdh\Debugbar\LaravelDebugbar;
use DebugBar\DataCollector\MessagesCollector;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SebastianBergmann\Version;
use Gitonomy\Git\Repository;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(){
        $debugbar = $this->app['debugbar'];
        if( $debugbar instanceof LaravelDebugbar ){
            $codeCollector = new MessagesCollector('code');
            $codeCollector->addMessage('enviroment:' . $this->app->environment() );
            //package "sebastian/version": "1.*"  required
            if( class_exists( Version::class ) ){
                $version = new Version( $this->app['config']['app']['version'] , base_path() );
                $codeCollector->addMessage('base path:' . base_path());
                $codeCollector->addMessage("version:\n" . $version->getVersion() );
            }
            if( class_exists( Repository::class ) ){
                $git = new \Gitonomy\Git\Repository(base_path());
                $codeCollector->addMessage("current commit:\n" . $git->getHeadCommit()->getRevision() );
            }
            $debugbar->addCollector( $codeCollector );
        }
    }

    public function register(){

    }
}
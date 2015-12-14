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
use Gitonomy\Git\Commit;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SebastianBergmann\Version;
use Gitonomy\Git\Repository;

/**
 * Class ServiceProvider
 * @description 在laravel debugbar中显示git仓库提交/版本/branch信息
 * @package Xjtuwangke\DebugInfo
 * @require "gitonomy/gitlib": "0.1.*"
 * @require "sebastian/version": "1.*"
 * @require "barryvdh/laravel-debugbar": "~2.0"
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * @throws \DebugBar\DebugBarException
     */
    public function boot(){
        $debugbar = $this->app['debugbar'];
        if( $debugbar instanceof LaravelDebugbar && $debugbar->isEnabled() ){

            /**
             * CodeCollector获取代码提交和版本信息
             */
            $codeCollector = new MessagesCollector('code');
            $codeCollector->addMessage('enviroment:' . $this->app->environment() );
            //package "sebastian/version": "1.*"  required
            if( class_exists( Version::class ) ){
                $version = static::appVersion( $this->app['config']->get('app.version') );
                $codeCollector->addMessage('base path:' . base_path());
                $codeCollector->addMessage("version:\n" . $version );
            }
            if( class_exists( Repository::class ) ){
                $repository = new \Gitonomy\Git\Repository(base_path());
                $head = $repository->getHeadCommit();
                $log = $head->getLog(null,null,5);
                $commits = array();
                foreach( $log->getCommits() as $commit ){
                    $commits[] = $this->commitMessageArray( $commit );
                }
                $codeCollector->addMessage("current commit:\n" . $head->getRevision() , 'HEAD' );
                $codeCollector->addMessage($commits , 'git-log');
            }
            $debugbar->addCollector( $codeCollector );

            /**
             * ServerCollector获取服务器信息
             */
            $serverCollector = new MessagesCollector('sever');
            $serverCollector->addMessage( php_uname() , 'uname' );
            $serverCollector->addMessage( "sapi_name:" . php_sapi_name() , 'phpinfo' );
            $serverCollector->addMessage( "PHP version:" . PHP_VERSION , 'phpinfo' );
            $serverCollector->addMessage( "Zend_Version:" . zend_version() , 'phpinfo' );
            $serverCollector->addMessage( sys_getloadavg() , 'load' );
            $debugbar->addCollector( $serverCollector );
        }
    }

    /**
     * @param Commit $commit
     * @return array
     */
    protected function commitMessageArray( Commit $commit ){
        return array(
            'hash' => $commit->getHash() ,
            'revision' => $commit->getRevision() ,
            'message' => $commit->getMessage() ,
            'committer' => $commit->getCommitterEmail() ,
            'data' => $commit->getCommitterDate() ,
        );
    }

    public function register(){

    }

    /**
     * @param $version
     * @return string X.Y.Z
     */
    public static function appVersion( $version = null ){
        if( ! $version ){
            $version = '0.0.0';
        }
        $version = new Version( $version , base_path() );
        return $version->getVersion();
    }
}
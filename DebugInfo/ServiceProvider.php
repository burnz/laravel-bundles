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

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @throws \DebugBar\DebugBarException
     */
    public function boot(){
        $debugbar = $this->app['debugbar'];
        if( $debugbar instanceof LaravelDebugbar ){
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
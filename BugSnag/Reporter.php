<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/16
 * Time: 16:33
 */

namespace Xjtuwangke\BugSnag;

use Xjtuwangke\BugSnag\Exception as BugSnagException;
use Xjtuwangke\DebugInfo\ServiceProvider as DebugInfoServiceProvider;

class Reporter
{
    /**
     * @param \Exception $e
     */
    public static function report( \Exception $e ){
        $bugsnag = app('bugsnag');
        if ( $bugsnag instanceof \Bugsnag_Client ) {
            $metadata = null;
            if( $e instanceof BugSnagException && $e->willBeReported() ){
                $metadata = $e->getExceptionMetaData();
                $metadata['app_version'] = DebugInfoServiceProvider::appVersion();
            }
            $bugsnag->notifyException($e, $metadata, "error");
        }
    }
}
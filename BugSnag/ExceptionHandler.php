<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/4
 * Time: 01:24
 */

namespace Xjtuwangke\BugSnag;

use Illuminate\Foundation\Exceptions\Handler;
use Xjtuwangke\Contracts\WithMessageBag\WithMessageBag;
use Xjtuwangke\BugSnag\Exception as BugSnagException;

class ExceptionHandler extends Handler {

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(\Exception $e)
    {
        return parent::report($e);
        if( $e instanceof DontReportExceptionContract ){
            return parent::report( $e );
        }
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                return parent::report($e);
            }
        }
        $bugsnag = app('bugsnag');
        if ( $bugsnag ) {
            $metadata = null;
            if( $e instanceof BugSnagException && $e->willBeReported() ){
                $metadata = $e->getExceptionMetaData();
                $metadata['app_version'] = \Config::get( 'app.version' );
            }
            $bugsnag->notifyException($e, $metadata, "error");
        }

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Exception $e)
    {
        if ($this->isHttpException($e))
        {
            return $this->renderHttpException($e);
        }


        if (config('app.debug'))
        {
            return $this->renderExceptionWithWhoops($e);
        }

        return parent::render($request, $e);
    }

    /**
     * Render an exception using Whoops.
     *
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    protected function renderExceptionWithWhoops(\Exception $e)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        return new \Illuminate\Http\Response(
            $whoops->handleException($e),
            $e->getStatusCode(),
            $e->getHeaders()
        );
    }
}
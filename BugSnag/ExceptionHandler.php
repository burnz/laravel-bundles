<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/4
 * Time: 01:24
 */

namespace Xjtuwangke\BugSnag;

use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Session\TokenMismatchException;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Xjtuwangke\BugSnag\Exception as BugsnagException;

/**
 * Class ExceptionHandler
 * @package Xjtuwangke\BugSnag
 */
class ExceptionHandler extends Handler {

    /**
     * Report or log an exception
     * @param \Exception $e
     */
    public function report(\Exception $e)
    {
        if( $e instanceof DontReportExceptionContract ){
            parent::report( $e );
        }
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                parent::report($e);
            }
        }
        Reporter::report( $e );
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return Response
     */
    public function render($request, \Exception $e)
    {
        if( $e instanceof TokenMismatchException ){
            $e = new Http500Exception('表单安全性验证失败,很可能是因为表单长期未提交已经过期,请返回上一页刷新页面');
        }
        if( $e instanceof MethodNotAllowedHttpException ){
            $e = new Http500Exception('打开方式不对@.@');
        }
        if ($this->isHttpException($e))
        {
            return $this->renderHttpException($e);
        }

        if( $e instanceof BugsnagException ){
            if( $request->acceptsHtml() ){
                $response = $e->getHtmlResponse();
            }
            else{
                $response = $e->getJsonResponse();
            }
            if( $response instanceof Response){
                return $response;
            }
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
     * @return Response
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
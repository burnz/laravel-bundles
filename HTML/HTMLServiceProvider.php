<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 01:50
 */

namespace Xjtuwangke\HTML;


use Illuminate\Html\FormBuilder;
use Illuminate\Html\HtmlBuilder;

class HTMLServiceProvider extends \Illuminate\Html\HtmlServiceProvider{

    /**
     * Register the HTML builder instance.
     *
     * @return void
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function($app)
        {
            return new HtmlBuilder($app['url']);
        });
    }

    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function($app)
        {
            $form = new FormBuilder($app['html'], $app['url'], $app['session.store']->getToken());

            return $form->setSessionStore($app['session.store']);
        });
    }
}
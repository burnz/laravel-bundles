<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/12
 * Time: 06:56
 */

namespace Xjtuwangke\HttpClient;


class ServiceProvider extends \Illuminate\Support\ServiceProvider{

    public function register(){
        $this->app->bind([ 'httpClient' => 'Xjtuwangke\HttpClient\Contract' ] , function( $app ){
            return new VinelabHttpClient();
        });
    }

    public function provides(){
        return array( 'httpClient' , 'Xjtuwangke\HttpClient\Contract' );
    }
}
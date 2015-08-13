<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 19:36
 */

Route::get('login' , [ 'as' => 'login' , 'uses' => 'LoginController@login' ]);
Route::post( 'login' , [ 'uses' => 'LoginController@login_action' ] );

Route::get('logout' , [ 'as' => 'logout' , 'uses' => 'LoginController@logout' ] );

Route::get('lock' , ['as' => 'lock' , 'uses' => 'LockController@lock'] );
Route::post('lock' , ['as' => 'unlock' , 'uses' => 'LockController@unlock'] );

Route::get( 'profile' , [ 'as' => 'profile.me' , 'uses' => 'ProfileController@profile'] );
Route::post( 'profile' , [ 'as' => 'profile.me.post' , 'uses' => 'ProfileController@postProfile'] );

Route::get( 'password' , [ 'as' => 'profile.password' , 'uses' => 'ProfileController@password'] );
Route::post( 'password' , [ 'as' => 'profile.password.post' , 'uses' => 'ProfileController@postPassword'] );
Route::get( 'success' , [ 'as' => 'profile.success' , 'uses' => 'ProfileController@success'] );
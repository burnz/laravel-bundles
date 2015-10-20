<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/10/12
 * Time: 22:17
 */

return array(
    'sk' => env('PINGPP_SECRET_KEY'),
    'pk'  => env('PINGPP_PUBLIC_KEY'),
    'charge' => array(
        'app'  => array(
            'id' => env('PINGPP_APP_ID'),
        ),
        'currency'  => 'cny',
        'client_ip' => '127.0.0.1',
        'subject'   => 'Your Subject',
        'body'      => 'Your Body',
    ),
);
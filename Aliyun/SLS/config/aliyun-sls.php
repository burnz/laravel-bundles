<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/20
 * Time: 15:14
 */
return array(
    'endpoint' => env('ALIYUN_SLS_ENDPOINT',''),
    'access_key_id' => env('ALIYUN_SLS_AK' , env('ALIYUN_AK')),
    'access_key' => env('ALIYUN_SLS_SK' , env('ALIYUN_SK')),
    'project' => env('PROJECT_NAME' , env('ALIYUN_SLS_PROJECT_NAME') ),
    'logstore' => env('ALIYUN_SLS_LOGSTORE',''),
);
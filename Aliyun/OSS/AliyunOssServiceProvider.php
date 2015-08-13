<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/15
 * Time: 17:33
 */

namespace Xjtuwangke\Aliyun\OSS;

use Storage;
use Aliyun\OSS\OSSClient;
use Orzcc\AliyunOss\AliyunOssAdapter;
use League\Flysystem\Filesystem;


class AliyunOssServiceProvider extends \Orzcc\AliyunOss\AliyunOssServiceProvider{

    public function boot()
    {
        Storage::extend('oss', function($app, $config)
        {
            $client = OSSClient::factory(array(
                'AccessKeyId'       => $config['access_id'],
                'AccessKeySecret'   => $config['access_key'] ,
                'Endpoint'          => $config['endpoint'] ,
            ));

            return new Filesystem(new AliyunOssAdapter($client, $config['bucket'], $config['prefix']));
        });
    }

    public function register()
    {
        //
    }
}
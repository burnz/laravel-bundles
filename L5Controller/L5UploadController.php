<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/5
 * Time: 18:44
 */

namespace Xjtuwangke\L5Controller;

use Response;
use Xjtuwangke\LaravelJobs\Jobs\UploadImage;

class L5UploadController extends L5Controller{

    /**
     * @var string
     */
    protected $imageJob = UploadImage::class;

    /**
     * @var string
     */
    protected static $salt = '564f92fc46611f3dd66a77119ff991df';

    /**
     * @param $key
     * @return string
     */
    public static function encrypt( $key ){
        return \Hash::make( $key . static::$salt );
    }

    /**
     * @param $key
     * @param $hashed
     * @return bool
     */
    public static function check( $key , $hashed ){
        return \Hash::check( $key . static::$salt , $hashed );
    }

    public function image(){
        $job = app()->make( $this->imageJob );
        $result = $job->handle();
        return Response::json( $result );
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/5
 * Time: 18:50
 */

namespace Xjtuwangke\LaravelJobs\Jobs;

use Xjtuwangke\LaravelJobs\Job;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Logging\Log as Log;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Image;
use File;

class UploadImage extends Job{

    protected $request;

    /**
     * @param Cache   $cache
     * @param Config  $config
     * @param Log     $logger
     * @param Request $request
     */
    public function __construct( Cache $cache , Config $config , Log $logger , Request $request ){
        $this->cache = $cache;
        $this->config = $config;
        $this->logger = $logger;
        $this->request = $request;
    }

    /**
     * @param int $width
     * @param int $height
     * @param int $quality
     * @return array
     */
    public function handle( $width = 600 , $height = 400 , $quality = 80 ){
        /**
         * object(Symfony\Component\HttpFoundation\FileBag)#45 (1) {
        ["parameters":protected]=>
        array(1) {
        ["Filedata"]=>
        object(Symfony\Component\HttpFoundation\File\UploadedFile)#30 (7) {
        ["test":"Symfony\Component\HttpFoundation\File\UploadedFile":private]=>
        bool(false)
        ["originalName":"Symfony\Component\HttpFoundation\File\UploadedFile":private]=>
        string(20) "1427418554198480.jpg"
        ["mimeType":"Symfony\Component\HttpFoundation\File\UploadedFile":private]=>
        string(10) "image/jpeg"
        ["size":"Symfony\Component\HttpFoundation\File\UploadedFile":private]=>
        int(493260)
        ["error":"Symfony\Component\HttpFoundation\File\UploadedFile":private]=>
        int(0)
        ["pathName":"SplFileInfo":private]=>
        string(14) "/tmp/phpnV2YwV"
        ["fileName":"SplFileInfo":private]=>
        string(9) "phpnV2YwV"
        }
        }
        }
         */
        $results = array();
        $file = $this->request->file('Filedata');
        if( $file && $file instanceof UploadedFile ){
            $ext = $file->guessExtension();
            if( ! in_array( $ext , [ 'jpg' , 'png' , 'bmp' , 'jpeg' , 'gif' ]) ){
                $results['error'] = "非法的文件类型:{$ext}";
            }
            else{
                $image = Image::make( $file->getPathname() )->resize( $width , $height ,function ($constraint) {
                    $constraint->aspectRatio();
                });
                $filename = sha1( time() . microtime() ) . '-' . rand( 0 , 65535 ) . rand( 0 , 65535 ) . '.' . $ext;
                $filepath = 'upload/images/' . date('Ym/d');
                if( ! File::exists( public_path( $filepath ) ) ){
                    File::makeDirectory( public_path( $filepath ) , 493 , true );
                }
                $image->save( public_path( $filepath . '/' . $filename ) , $quality );
                $results['url'] = url( $filepath . '/' . $filename );
                $results['key'] = $filepath . '/' . $filename;
            }
        }
        else{
            $results['error'] = '服务器没有收到上传的文件';
        }
        return $results;
    }
}
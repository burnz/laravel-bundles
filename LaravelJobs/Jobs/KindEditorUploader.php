<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/18
 * Time: 17:06
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

class KindEditorUploader extends Job{

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
        $results = array();
        $file = $this->request->file('imgFile');
        if( $file && $file instanceof UploadedFile ){
            $ext = $file->guessExtension();
            if( ! in_array( $ext , [ 'jpg' , 'png' , 'bmp' , 'jpeg' , 'gif' ]) ){
                $results['message'] = "非法的文件类型:{$ext}";
                $results['error']  = 1;
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
                $results['error'] = 0;
            }
        }
        else{
            $results['message'] = '服务器没有收到上传的文件';
            $results['error']  = 1;
        }
        return $results;
    }
}
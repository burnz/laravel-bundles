<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 20:42
 */

namespace Xjtuwangke\StoredFile\Concretes;


use Xjtuwangke\StoredFile\Contracts\StoredFileContract;
use Illuminate\Contracts\Filesystem\Filesystem;

class OSSFile extends LocalStoredFile implements StoredFileContract{

    /**
     * 获取Filesystem
     * @return Filesystem
     */
    public static function getFileSystem(){
        if( is_null( static::$driver ) ){
            static::$driver = \Storage::drive('oss');
        }
        return static::$driver;
    }

    /**
     * @override
     * @return string | null
     * @like http://igoodish.oss-cn-hangzhou.aliyuncs.com/images%2Favatar%2F2013-08%2F1e1e9d5ec9d9513a9655ed9268e2b514_5979_300x180_100X150.jpg
     */
    public function getURL(){
        return 'http://igoodish.oss-cn-hangzhou.aliyuncs.com/' . urlencode( $this->fullKey( $this->getKey() ) );
    }
}
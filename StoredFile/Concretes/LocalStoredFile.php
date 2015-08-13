<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 20:00
 */

namespace Xjtuwangke\StoredFile\Concretes;


use Xjtuwangke\StoredFile\Contracts\StoredFileContract;
use Illuminate\Contracts\Filesystem\Filesystem;

class LocalStoredFile implements StoredFileContract{

    const prefix = '';

    protected $key;

    protected static $driver = null;

    /**
     * @param string $key
     */
    public function __construct( $key ){
        $this->key = $key;
    }

    /**
     * 获取Filesystem
     * @return Filesystem
     */
    public static function getFileSystem(){
        if( is_null( static::$driver ) ){
            static::$driver = \Storage::drive('local');
        }
        return static::$driver;
    }

    /**
     * 向文件系统写内容
     * @param $key
     * @param $content string | resource
     * @return StoredFileContract | null
     */
    public static function create( $key , $content ){
        $instance = new static( $key );
        if( $instance->put( $content ) ){
            return $instance;
        }
        else{
            return null;
        }
    }

    /**
     * @param $content
     * @return boolean
     */
    public function put( $content ){
        $driver = $this->getFileSystem();
        return $driver->put( $this->fullKey( $this->getKey() ) , $content );
    }

    /**
     * @param $content string
     * @return bool
     */
    public function append( $content ){
        $driver = $this->getFileSystem();
        return $driver->append( $this->fullKey( $this->getKey() ) , $content );
    }

    /**
     * @param $key
     * @return string
     */
    protected static function fullKey( $key ){
        return static::prefix . $key;
    }

    /**
     * 根据key查找payload
     * @param $key
     * @return StoredFileContract | null
     */
    public static function find( $key ){
        $instance = new static( $key );
        if( $instance->exists() ){
            return $instance;
        }
        else{
            return null;
        }
    }

    /**
     * @return string
     */
    public function getKey(){
        return $this->key;
    }

    /**
     * @return bool
     */
    public function remove(){
        $driver = $this->getFileSystem();
        return $driver->delete( $this->fullKey( $this->getKey() ) );
    }

    /**
     * @param $key
     * @return StoredFileContract | null
     */
    public function copyTo( $key ){
        $driver = $this->getFileSystem();
        $origin = $this->fullKey( $this->getKey() );
        $target = $this->fullKey( $key );
        if( $driver->copy( $origin , $target ) ){
            return new static( $key );
        }
        else{
            return null;
        }
    }

    /**
     * @param $key
     * @return StoredFileContract | null
     */
    public function moveTo( $key ){
        $driver = $this->getFileSystem();
        $driver->
        $origin = $this->fullKey( $this->getKey() );
        $target = $this->fullKey( $key );
        if( $driver->move( $origin , $target ) ){
            return new static( $key );
        }
        else{
            return null;
        }
    }

    /**
     * 文件是否存在
     * @return bool
     */
    public function exists(){
        $driver = static::getFileSystem();
        if( $driver->exists( $this->fullKey( $this->getKey() ) ) ){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 返回文件的MimeType
     * @return string
     */
    public function getMimeType(){
        return 'application/octet-stream';
    }

    /**
     * 返回URL
     * @return string | null
     */
    public function getURL(){
        return null;
    }
}
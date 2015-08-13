<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 19:53
 */

namespace Xjtuwangke\StoredFile\Contracts;

use Illuminate\Contracts\Filesystem\Filesystem;

interface StoredFileContract {

    /**
     * 获取Filesystem
     * @return Filesystem
     */
    public static function getFileSystem();

    /**
     * 向文件系统写内容
     * @param $key
     * @param $content string | resource
     * @return StoredFileContract | null
     */
    public static function create( $key , $content );

    /**
     * @param $content
     * @return boolean
     */
    public function put( $content );

    /**
     * @param $content string
     * @return boolean
     */
    public function append( $content );

    /**
     * 根据key查找
     * @param $key
     * @return mixed
     */
    public static function find( $key );

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return bool
     */
    public function remove();

    /**
     * @param $key
     * @return mixed
     */
    public function copyTo( $key );

    /**
     * @param $key
     * @return mixed
     */
    public function moveTo( $key );

    /**
     * 文件是否存在
     * @return boolean
     */
    public function exists();

    /**
     * 返回文件的MimeType
     * @return string
     */
    public function getMimeType();

    /**
     * 返回URL
     * @return mixed
     */
    public function getURL();
}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/18
 * Time: 20:01
 */

namespace Xjtuwangke\Printings;


use Illuminate\Contracts\Logging\Log;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Exception\HttpResponseException;
use Response;
use File;

abstract class Printing
{

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Log
     */
    protected $logger;

    /**
     * @var
     */
    protected $filename;

    /**
     * @var
     */
    protected $path;

    /**
     * @param Log $logger
     */
    public function __construct( Log $logger ){
        $this->logger = $logger;
        $this->setPath() ;
    }

    /**
     * @return string
     */
    public function getPath(){
        return $this->path ;
    }

    /**
     * @param null|string $path
     */
    public function setPath( $path = null ){
        if( $path ){
            $this->path = $path;
        }
        else{
            $this->path = 'app/printings_' . $this->getExtension();
        }
        if( ! File::isDirectory( storage_path( $this->path ) ) ){
            File::makeDirectory( storage_path( $this->path ) );
        }
    }

    /**
     * 初始化电子出版物
     * @param Config $config
     * @return mixed
     */
    abstract public function initialization( Config $config );

    /**
     * @return string
     */
    abstract public function getExtension();

    /**
     * @return string
     */
    abstract public function getMimeType();

    /**
     * @param Chapter $chapter
     * @return $this
     */
    abstract public function addChapter( Chapter $chapter );

    /**
     * @param $filename string 文件名,无后缀
     * @return mixed
     */
    abstract public function save( $filename = null );

    /**
     * @return string
     */
    public function getFullPath(){
        return ltrim( $this->getPath() , '/' ) . '/' . $this->filename . '.' . $this->getExtension();
    }

    /**
     *
     */
    abstract public function streamContent();

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function streamResponse(){
        $response = Response::stream(function(){
            $this->streamContent();
        },200,array('Content-Type'=>$this->getMimeType()));
        throw new HttpResponseException( $response );
    }

}
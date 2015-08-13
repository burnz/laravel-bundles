<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/7
 * Time: 17:01
 */

namespace Xjtuwangke\HttpClient\ClientRequests;

class ClientRequest {

    const GET = "GET";
    const POST = "POST";
    const PUT  = "PUT";
    const DELETE = "DELETE";

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var string
     */
    protected $content = "";

    /**
     * @var string
     */
    protected $verb;

    /**
     * @var array
     */
    protected $headers = array();

    /**
     * @var string | null
     */
    protected $userAgent;

    /**
     * @var string | null
     */
    protected $cookie;

    /**
     * @var array | null
     */
    protected $proxy;

    /**
     * @var array | null
     */
    protected $authorization;

    /**
     * @var string
     */
    protected $referer;

    /**
     * @var array
     */
    protected $files = array();

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @param       $url
     * @param null  $verb
     * @param array $headers
     */
    public function __construct( $url , $verb = null , $headers = array() ){
        $this->url = $url;
        if( is_null( $verb ) ){
            $this->verb = static::GET;
        }
        else{
            $this->verb = $verb;
        }
        $this->headers = array_merge( $this->headers , $headers );
    }

    /**
     * @return mixed
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * @param $timeout
     * @return $this
     */
    public function setTimeout( $timeout ){
        $this->timeout = (int) $timeout;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimeout(){
        return $this->timeout;
    }

    /**
     * @return mixed
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @param $cookie
     * @return $this
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @param $host
     * @param $port
     * @return $this
     */
    public function setProxy( $host , $port )
    {
        $this->proxy = array( 'host' => $host , 'port' => $port );
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @param $user
     * @param $pass
     * @return $this
     */
    public function setAuthorization( $user , $pass )
    {
        $this->authorization = array( 'user' => $user , 'pass' => $pass );
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param $referer
     * @return $this
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
        return $this;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param        $field
     * @param        $file
     * @param string $mime
     * @return void
     */
    public function setFile( $field , $file  , $mime = '' ){
        $this->files[$field] = array(
            'file' => $file ,
            'mime' => $mime );
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders( array $headers )
    {
        $this->headers = $headers;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setHeader( $key , $value ){
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        $param = "";
        foreach( $this->getParameters() as $key => $val ){
            $param.= $key . "=" . urlencode( $val ) . "&";
        }
        if( strlen( $param ) > 0 ){
            $param = rtrim( $param , "&" );
            return $this->url . "?" . $param;
        }
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUri(){
        $uri = "/";
        if( preg_match( '/^((http|https)\:\/\/){0,1}([\d\w\.\-\_]+)([^?]*)(\?){0,1}(.*)/i' , $this->getUrl() , $matches ) ){
            $uri = $matches[4];
        }
        return $uri;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters( array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        if( is_array( $content ) ){
            $content = http_build_query( $content );
        }
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function toArray(){
        return array(
            'url' => $this->getUrl() ,
            'verb' => $this->getVerb() ,
            'content' => $this->getContent() ,
            'headers' => $this->getHeaders() ,
            'parameters' => $this->getParameters() ,
        );
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/20
 * Time: 14:37
 */

namespace Xjtuwangke\Aliyun\SLS;

use Xjtuwangke\Aliyun\SLS\Models\GetHistogramsRequest;
use Xjtuwangke\Aliyun\SLS\Models\GetHistogramsResponse;
use Xjtuwangke\Aliyun\SLS\Models\GetLogsRequest;
use Xjtuwangke\Aliyun\SLS\Models\GetLogsResponse;
use Xjtuwangke\Aliyun\SLS\Models\Histogram;
use Xjtuwangke\Aliyun\SLS\Models\ListLogstoresRequest;
use Xjtuwangke\Aliyun\SLS\Models\ListLogstoresResponse;
use Xjtuwangke\Aliyun\SLS\Models\ListTopicsRequest;
use Xjtuwangke\Aliyun\SLS\Models\ListTopicsResponse;
use Xjtuwangke\Aliyun\SLS\Models\LogItem;
use Xjtuwangke\Aliyun\SLS\Models\PutLogsRequest;
use Xjtuwangke\Aliyun\SLS\Models\PutLogsResponse;
use Xjtuwangke\Aliyun\SLS\Models\QueriedLog;
use Xjtuwangke\Aliyun\SLS\Models\Request;
use Xjtuwangke\Aliyun\SLS\Models\Response;

use Xjtuwangke\Aliyun\SLS\RequestCore\RequestCore;
use Xjtuwangke\Aliyun\SLS\SLSProto\LogGroup;
use Xjtuwangke\Aliyun\SLS\SLSProto\Log;
use Xjtuwangke\Aliyun\SLS\SLSProto\Log_Content;


class Client
{
    const API_VERSION = '0.4.0';

    const USER_AGENT = 'sls-php-sdk-v-0.4.1';

    /**
     * @var string aliyun accessKey
     */
    protected $accessKey;

    /**
     * @var string aliyun accessKeyId
     */
    protected $accessKeyId;

    /**
     * @var string SLS endpoint
     */
    protected $endpoint;

    /**
     * @var string Check if the host if row ip.
     */
    protected $isRowIp;

    /**
     * @var integer Http send port. The dafault value is 80.
     */
    protected $port;

    /**
     * @var string sls sever host.
     */
    protected $slsHost;

    /**
     * @var string the local machine ip address.
     */
    protected $source;

    /**
     * Client constructor
     *
     * @param string $endpoint
     *            SLS host name, for example, http://ch-hangzhou.sls.aliyuncs.com
     * @param string $accessKeyId
     *            aliyun accessKeyId
     * @param string $accessKey
     *            aliyun accessKey
     */
    public function __construct($endpoint, $accessKeyId, $accessKey) {
        $this->setEndpoint ( $endpoint ); // set $this->slsHost
        $this->accessKeyId = $accessKeyId;
        $this->accessKey = $accessKey;
        $this->source = Util::getLocalIp();
    }
    private function setEndpoint($endpoint) {
        $pos = strpos ( $endpoint, "://" );
        if ($pos !== false) { // be careful, !==
            $pos += 3;
            $endpoint = substr ( $endpoint, $pos );
        }
        $pos = strpos ( $endpoint, "/" );
        if ($pos !== false) // be careful, !==
            $endpoint = substr ( $endpoint, 0, $pos );
        $pos = strpos ( $endpoint, ':' );
        if ($pos !== false) { // be careful, !==
            $this->port = ( int ) substr ( $endpoint, $pos + 1 );
            $endpoint = substr ( $endpoint, 0, $pos );
        } else
            $this->port = 80;
        $this->isRowIp = Util::isIp ( $endpoint );
        $this->slsHost = $endpoint;
        $this->endpoint = $endpoint . ':' . ( string ) $this->port;
    }

    /**
     * GMT format time string.
     *
     * @return string
     */
    protected function getGMT() {
        return gmdate ( 'D, d M Y H:i:s' ) . ' GMT';
    }

    /**
     * Decodes a JSON string.
     * Unsuccessful decode will cause an Exception.
     *
     * @return string
     * @throws Exception
     */
    protected function loadJson($json, $requestId) {
        if (! $json)
            return NULL;
        $json = json_decode ( $json, true );
        if ($json === NULL)
            throw new Exception ( 'SLSBadResponse', "Bad json format: $json", $requestId );
        return $json;
    }

    /**
     * @return array
     */
    protected function getHttpResponse($method, $url, $body, $headers) {
        $request = new RequestCore ( $url );
        foreach ( $headers as $key => $value )
            $request->add_header ( $key, $value );
        $request->set_method ( $method );
        $request->set_useragent(static::USER_AGENT);
        if ($method == "POST")
            $request->set_body ( $body );
        $request->send_request ();
        $response = array ();
        $response [] = ( int ) $request->get_response_code ();
        $response [] = $request->get_response_header ();
        $response [] = $request->get_response_body ();
        return $response;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function sendRequest($method, $url, $body, $headers) {
        try {
            list ( $responseCode, $header, $exJson ) =
                $this->getHttpResponse ( $method, $url, $body, $headers );
        } catch ( Exception $ex ) {
            throw new Exception ( $ex->getMessage (), $ex->__toString () );
        }

        $requestId = isset ( $header ['x-sls-requestid'] ) ? $header ['x-sls-requestid'] : '';
        $exJson = $this->loadJson ( $exJson, $requestId );
        if ($responseCode == 200) {
            return array (
                $exJson,
                $header
            );
        } else {
            if (isset($exJson ['error_code']) && isset($exJson ['error_message'])) {
                throw new Exception ( $exJson ['error_code'],
                    $exJson ['error_message'], $requestId );
            } else {
                if ($exJson) {
                    $exJson = ' The return json is ' . json_encode($exJson);
                } else {
                    $exJson = '';
                }
                throw new Exception ( 'SLSRequestError',
                    "Request is failed. Http code is $responseCode.$exJson", $requestId );
            }
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    private function send($method, $project, $body, $resource, $params, $headers) {
        if ($body) {
            $headers ['Content-Length'] = strlen ( $body );
            $headers ['Content-MD5'] = Util::calMD5 ( $body );
            $headers ['Content-Type'] = 'application/x-protobuf';
        } else {
            $headers ['Content-Length'] = 0;
            $headers ["x-sls-bodyrawsize"] = 0;
            $headers ['Content-Type'] = ''; // If not set, http request will add automatically.
        }

        $headers ['x-sls-apiversion'] = static::API_VERSION;
        $headers ['x-sls-signaturemethod'] = 'hmac-sha1';
        $headers ['Host'] = "$project.$this->slsHost";
        $headers ['Date'] = $this->GetGMT ();

        $signature = Util::getRequestAuthorization ( $method, $resource, $this->accessKey, $params, $headers );
        $headers ['Authorization'] = "SLS $this->accessKeyId:$signature";

        $url = $resource;
        if ($params)
            $url .= '?' . Util::urlEncode ( $params );
        if ($this->isRowIp)
            $url = "http://$this->endpoint$url";
        else
            $url = "http://$project.$this->endpoint$url";
        return $this->sendRequest ( $method, $url, $body, $headers );
    }

    /**
     * Put logs to SLS.
     * Unsuccessful opertaion will cause an Exception.
     *
     * @param PutLogsRequest $request the PutLogs request parameters class
     * @throws Exception
     * @return PutLogsResponse
     */
    public function putLogs(PutLogsRequest $request) {
        if (count ( $request->getLogitems () ) > 4096)
            throw new Exception ( 'InvalidLogSize', "logItems' length exceeds maximum limitation: 4096 lines." );

        $logGroup = new LogGroup ();
        $topic = $request->getTopic () !== null ? $request->getTopic () : '';
        $logGroup->setTopic ( $request->getTopic () );
        $source = $request->getSource ();
        if ( ! $source )
            $source = $this->source;
        $logGroup->setSource ( $source );
        $logitems = $request->getLogitems ();
        foreach ( $logitems as $logItem ) {
            $log = new Log ();
            $log->setTime ( $logItem->getTime () );
            $content = $logItem->getContents ();
            foreach ( $content as $key => $value ) {
                $content = new Log_Content ();
                $content->setKey ( $key );
                $content->setValue ( $value );
                $log->addContents ( $content );
            }
            $logGroup->addLogs ( $log );
        }
        $body = Util::toBytes ( $logGroup );
        unset ( $logGroup );

        $bodySize = strlen ( $body );
        if ($bodySize > 3 * 1024 * 1024) // 3 MB
            throw new Exception ( 'InvalidLogSize', "logItems' size exceeds maximum limitation: 3 MB." );
        $params = array ();
        $headers = array ();
        $headers ["x-sls-bodyrawsize"] = $bodySize;
        $headers ['x-sls-compresstype'] = 'deflate';
        $body = gzcompress ( $body, 6 );

        $logstore = $request->getLogstore () !== null ? $request->getLogstore () : '';
        $project = $request->getProject () !== null ? $request->getProject () : '';
        $resource = "/logstores/" . $logstore;
        list ( $resp, $header ) = $this->send ( "POST", $project, $body, $resource, $params, $headers );
        return new PutLogsResponse ( $header );
    }

    /**
     * List all logstores of requested project.
     * Unsuccessful opertaion will cause an Exception.
     *
     * @param ListLogstoresRequest $request the ListLogstores request parameters class.
     * @throws Exception
     * @return ListLogstoresResponse
     */
    public function listLogstores(ListLogstoresRequest $request) {
        $headers = array ();
        $params = array ();
        $resource = '/logstores';
        $project = $request->getProject () !== null ? $request->getProject () : '';
        list ( $resp, $header ) = $this->send ( "GET", $project, NULL, $resource, $params, $headers );
        return new ListLogstoresResponse ( $resp, $header );
    }

    /**
     * List all topics in a logstore.
     * Unsuccessful opertaion will cause an Exception.
     *
     * @param ListTopicsRequest $request the ListTopics request parameters class.
     * @throws Exception
     * @return ListTopicsResponse
     */
    public function listTopics(ListTopicsRequest $request) {
        $headers = array ();
        $params = array ();
        if ($request->getToken () !== null)
            $params ['token'] = $request->getToken ();
        if ($request->getLine () !== null)
            $params ['line'] = $request->getLine ();
        $params ['type'] = 'topic';
        $logstore = $request->getLogstore () !== null ? $request->getLogstore () : '';
        $project = $request->getProject () !== null ? $request->getProject () : '';
        $resource = "/logstores/$logstore";
        list ( $resp, $header ) = $this->send ( "GET", $project, NULL, $resource, $params, $headers );
        return new ListTopicsResponse ( $resp, $header );
    }

    /**
     * Get histograms of requested query from SLS.
     * Unsuccessful opertaion will cause an Exception.
     *
     * @param GetHistogramsRequest $request the GetHistograms request parameters class.
     * @throws Exception
     * @return GetHistogramsResponse
     */
    public function getHistograms(GetHistogramsRequest $request) {
        $headers = array ();
        $params = array ();
        if ($request->getTopic () !== null)
            $params ['topic'] = $request->getTopic ();
        if ($request->getFrom () !== null)
            $params ['from'] = $request->getFrom ();
        if ($request->getTo () !== null)
            $params ['to'] = $request->getTo ();
        if ($request->getQuery () !== null)
            $params ['query'] = $request->getQuery ();
        $params ['type'] = 'histogram';
        $logstore = $request->getLogstore () !== null ? $request->getLogstore () : '';
        $project = $request->getProject () !== null ? $request->getProject () : '';
        $resource = "/logstores/$logstore";
        list ( $resp, $header ) = $this->send ( "GET", $project, NULL, $resource, $params, $headers );
        return new GetHistogramsResponse ( $resp, $header );
    }

    /**
     * Get logs from SLS.
     * Unsuccessful opertaion will cause an Exception.
     *
     * @param GetLogsRequest $request the GetLogs request parameters class.
     * @throws Exception
     * @return GetLogsResponse
     */
    public function getLogs(GetLogsRequest $request) {
        $headers = array ();
        $params = array ();
        if ($request->getTopic () !== null)
            $params ['topic'] = $request->getTopic ();
        if ($request->getFrom () !== null)
            $params ['from'] = $request->getFrom ();
        if ($request->getTo () !== null)
            $params ['to'] = $request->getTo ();
        if ($request->getQuery () !== null)
            $params ['query'] = $request->getQuery ();
        $params ['type'] = 'log';
        if ($request->getLine () !== null)
            $params ['line'] = $request->getLine ();
        if ($request->getOffset () !== null)
            $params ['offset'] = $request->getOffset ();
        if ($request->getOffset () !== null)
            $params ['reverse'] = $request->getReverse () ? 'true' : 'false';
        $logstore = $request->getLogstore () !== null ? $request->getLogstore () : '';
        $project = $request->getProject () !== null ? $request->getProject () : '';
        $resource = "/logstores/$logstore";
        list ( $resp, $header ) = $this->send ( "GET", $project, NULL, $resource, $params, $headers );
        return new GetLogsResponse ( $resp, $header );
    }
}
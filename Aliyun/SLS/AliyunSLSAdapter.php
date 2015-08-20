<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/20
 * Time: 15:09
 */

namespace Xjtuwangke\Aliyun\SLS;

use Xjtuwangke\Aliyun\SLS\Models\GetHistogramsRequest;
use Xjtuwangke\Aliyun\SLS\Models\GetLogsRequest;
use Xjtuwangke\Aliyun\SLS\Models\ListLogstoresRequest;
use Xjtuwangke\Aliyun\SLS\Models\ListLogstoresResponse;
use Xjtuwangke\Aliyun\SLS\Models\LogItem;
use Xjtuwangke\Aliyun\SLS\Models\PutLogsRequest;

class AliyunSLSAdapter
{
    protected $client;
    protected $project;
    protected $logstore;

    public function __construct($endpoint, $access_key_id, $access_key, $project = '', $logstore = '')
    {
        $this->client = new Client($endpoint, $access_key_id, $access_key);
        $this->project = $project;
        $this->logstore = $logstore;
    }
    /**
     * 列出Project下的所有Logstore名称。
     *
     * @param string $project
     * @return ListLogstoresResponse
     */
    public function listLogstores($project = '')
    {
        $project = $project ?  : $this->project;
        $request = new ListLogstoresRequest($project);
        $response = $this->client->listLogstores($request);
        return $response;
    }
    /**
     * 向指定的Logstore写入日志。
     *
     * @param string $topic
     * @param array $contents
     * @param string $project
     * @param string $logstore
     * @return boolean
     */
    public function putLogs($topic, $contents, $project = '', $logstore = '')
    {
        $project = $project ?  : $this->project;
        $logstore = $logstore ?  : $this->logstore;
        $log_item = new LogItem();
        $log_item->setTime(time());
        $log_item->setContents($contents);
        $logitems = [
            $log_item
        ];
        $request = new PutLogsRequest($project, $logstore, $topic, null, $logitems);
        $response = $this->client->putLogs($request);
        return array_get($response->getAllHeaders(), '_info.http_code') === 200;
    }

    /**
     * 列出Logstore中的日志主题
     * @param string $project
     * @param string $logstore
     * @return Models\ListTopicsResponse
     */
    public function listTopics($project = '', $logstore = '')
    {
        $project = $project ?  : $this->project;
        $logstore = $logstore ?  : $this->logstore;
        $request = new ListLogstoresRequest($project, $logstore);
        $response = $this->client->listTopics($request);
        return $response;
    }

    /**
     * 查询Logstore中的日志在时间轴上的分布
     *
     * @param null $from
     * @param null $to
     * @param null $topic
     * @param null $query
     * @param null $project
     * @param null $logstore
     * @return Models\GetHistogramsResponse
     */
    public function getHistograms($from = null, $to = null, $topic = null, $query = null, $project = null, $logstore = null)
    {
        $project = $project ?  : $this->project;
        $logstore = $logstore ?  : $this->logstore;
        $request = new GetHistogramsRequest($project, $logstore, $from, $to, $topic, $query);
        $response = $this->client->getHistograms($request);
        return $response;
    }

    /**
     * 查询Logstore中的日志数据
     *
     * @param null $from
     * @param null $to
     * @param null $topic
     * @param null $query
     * @param int $line
     * @param null $offset
     * @param null $reverse
     * @param null $project
     * @param null $logstore
     * @return Models\GetLogsResponse
     */
    public function getLogs($from = null, $to = null, $topic = null, $query = null, $line = 100, $offset = null, $reverse = null, $project = null, $logstore = null)
    {
        $project = $project ?  : $this->project;
        $logstore = $logstore ?  : $this->logstore;
        $request = new GetLogsRequest($project, $logstore, $from, $to, $topic, $query, $line, $offset, $reverse);
        $response = $this->client->getLogs($request);
        return $response;
    }
}
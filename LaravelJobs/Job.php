<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/12
 * Time: 04:59
 */

namespace Xjtuwangke\LaravelJobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Logging\Log as Log;

class Job  implements SelfHandling{

    /**
     * @var Log 实现日志功能
     */
    protected $logger;

    /**
     * @var Cache 实现缓存功能
     */
    protected $cache;

    /**
     * @var Config 实现读取配置功能
     */
    protected $config;

    /**
     * @param Cache  $cache
     * @param Config $config
     * @param Log    $logger
     */
    public function __construct( Cache $cache , Config $config , Log $logger ){
        $this->cache = $cache;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function handle(){

    }

}
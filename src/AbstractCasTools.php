<?php
/**
 * Created by Kane
 * Date: 2020/11/2
 */

namespace datatom\casTools;

use Monolog\Formatter\LineFormatter;
use \Monolog\Logger;
use Monolog\Handler\StreamHandler;
use datatom\casTools\Auth;
use datatom\casTools\Utils;
use datatom\casTools\Request;

use const datatom\casTools\DEFAULT_LOG_FILE;
use const datatom\casTools\DEFAULT_LOG_LEVEL;


abstract class AbstractCasTools {
    protected $url;
    protected $auth;
    protected $logger;
    protected $host;
    protected $logFile;
    protected $logLevel;
    protected $request;

    function __construct(string $api,
                         Auth $auth,
                         string $logFile = null,
                         int $logLevel = null) {
        $this->auth = $auth;
        $this->host = $this->auth->host;

        // 处理日志
        $logFile = $logFile == null ? DEFAULT_LOG_FILE : $logFile;
        $logLevel = $logLevel == null ? DEFAULT_LOG_LEVEL : $logLevel;
        $this->logger = new Logger("cas-tools");
        $handler = new StreamHandler($logFile, $logLevel);
        $formatter = new LineFormatter(null, 'Y-m-d H:i:s', false, true);
        $handler->setFormatter($formatter);

        $this->logger->pushHandler($handler);

        // 拼完整url路径
        if ($api == null) {
            $this->url = $this->host;
        } else {
            $this->url = Utils::urlJoin($this->host, $api);
        }
        $this->logger->info("URL is " . $this->url . "\n");
        $this->request = new Request($this->logger);
    }

    function post($data) {
        $ts = time();
        $signature = $this->auth->doSignature($data, $ts);
        $wrapped_data = [
            'signature' => $signature,
            'nonce' => $ts,
            'data' => $data,
        ];
        return $this->request->doPost($this->url, $wrapped_data);
    }

    public function getUrl() {
        return $this->url;
    }

    public function getApi() {
        return $this->api;
    }

    function getSysInfo() {
        $memoryUsage = null;
        try {
            $memoryUsage = Utils::memoryUsage();
        } catch (Exception $exception) {
            $this->logger->warning("get mermory usage fail");
        }
        return array(
            "os_name" => php_uname("s"),
            "os_version" => php_uname("v"),
            "web_server" => "Nginx",
            "web_server_version" => "1.16.1",
            "application_server" => "fpm-fcgi",
            "application_server_version" => PHP_VERSION,
            "language" => "php",
            "language_version" => PHP_VERSION,
            "sys_load" => implode(",", sys_getloadavg()),
            "cup_usage" => sys_getloadavg()[0],
            "memory_usage" => $memoryUsage,
            "sync_interval" => 300,
            "version" => "V1",
            "description" => "cas=tools"
        );
    }
}

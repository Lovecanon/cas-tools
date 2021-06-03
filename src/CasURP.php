<?php
/**
 * Created by Kane
 * Date: 2020/11/2
 */

namespace datatom\casTools;

use datatom\casTools\AbstractCasTools;
use datatom\casTools\Utils;
use \Exception;

class CasURP extends AbstractCasTools {
    protected $api = "sync/urp";

    function __construct(Auth $auth,
                         string $logFile = null,
                         int $logLevel = null,
                         string $api = null) {
        $this->api = $api == null ? $this->api : $api;
        parent::__construct($this->api, $auth, $logFile, $logLevel);
    }

    function sync(array $roleArray, array $guids) {
        if ($guids == null) {
            $guids = [];
        }
        $data = [
            "stats" => $this->getSysInfo(),
            "role" => $roleArray,
            "ack" => $guids
        ];

        $this->logger->info("");
        $this->logger->info("------------ sync start ------------");
        $this->logger->info(json_encode($data));
        $ret = $this->post($data);
        $this->logger->info(json_encode($ret));
        $this->logger->info("------------ sync end ------------");
        $this->logger->info("");
        return $ret;
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
            "description" => ""
        );
    }
}
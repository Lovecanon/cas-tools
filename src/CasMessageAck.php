<?php
/**
 * Created by Kane
 * Date: 2020/11/2
 */

namespace datatom\casTools;

use datatom\casTools\AbstractCasTools;
use datatom\casTools\Utils;
use \Exception;

class CasMessageAck extends AbstractCasTools {
    protected $api = "sync/urp/ack";

    function __construct(Auth $auth,
                         string $logFile = null,
                         int $logLevel = null,
                         string $api = null) {
        $this->api = $api == null ? $this->api : $api;
        parent::__construct($this->api, $auth, $logFile, $logLevel);
    }

    function ack(array $guids) {
        $data = [
            "stats" => $this->getSysInfo(),
            "ack" => $guids
        ];

        $this->logger->info("");
        $this->logger->info("------------ ack start ------------");
        $this->logger->info(json_encode($data));
        $ret = $this->post($data);
        $this->logger->info(json_encode($ret));
        $this->logger->info("------------ ack end ------------");
        $this->logger->info("");
        return $ret;
    }
}
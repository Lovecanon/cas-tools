<?php
/**
 * Created by Kane
 * Date: 2020/10/29
 */

namespace datatom\casTools;


use datatom\casTools\AbstractCasTools;

class CasToken extends AbstractCasTools {
    protected $api = "applications/accountToken";

    function __construct(Auth $auth,
                         string $logFile = null,
                         int $logLevel = null,
                         string $api = null) {
        $this->api = $api == null ? $this->api : $api;
        parent::__construct($this->api, $auth, $logFile, $logLevel);
    }

    function verifyToken(string $token) {
        $data = [
            "token" => $token
        ];
        return $this->post($data);
    }
}

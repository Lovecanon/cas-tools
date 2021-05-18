<?php
/**
 * Created by Kane
 * Date: 2021/5/18
 */

namespace datatom\casTools;

use Exception;
use Buzz\Browser;
use Buzz\Client\FileGetContents;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Log\LoggerInterface;


class Request {
    protected $browser;
    protected $logger;
    protected $headers = ["Content-Type" => "application/json;charset=utf-8"];

    function __construct(LoggerInterface $logger) {
        $client = new FileGetContents(new Psr17Factory());
        $this->browser = new Browser($client, new Psr17Factory());
        $this->logger = $logger;
    }

    public function doPost(string $url, array $data) {
        $ret = null;
        $msg = "verify token fail, ";
        try {
            $response = $this->browser->post($url, $this->headers, json_encode($data, JSON_UNESCAPED_SLASHES));
        } catch (Exception $e) {
            $msg .= "HTTP request error, err: " . $e->getMessage();
            $this->logger->error($msg);
            throw new Exception($msg, 500);
        }
        $resp_code = $response->getStatusCode();
        if ($resp_code >= 500) {
            $msg .= "internal server error, status code " . $resp_code;
            $this->logger->error($msg);
            throw new Exception($msg, 500);
        } else if (400 <= $resp_code) {
            $err_data = json_decode($response->getBody()->getContents(), true);
            if (isset($err_data["msg"])) {
                $msg .= "err: " . $err_data["msg"];
                $this->logger->error($msg);
                throw new Exception($msg, $resp_code);
            } else {
                $msg .= "err: unknown, status code: " . $resp_code;
                $this->logger->error($msg);
                throw new Exception($msg, $resp_code);
            }
        } else if (200 <= $resp_code && $resp_code < 300) {
            $this->logger->info("verify token success");
            $ret = json_decode($response->getBody()->getContents(), true);
        } else {
            $msg .= "unrecognized response code" . $resp_code;
            $this->logger->error($msg);
            throw new Exception($msg, $resp_code);
        }
        return $ret;
    }


}





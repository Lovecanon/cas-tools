<?php
/**
 * Created by Kane
 * Date: 2021/5/18
 */

namespace datatom\test;

use datatom\casTools\Auth;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase {

    public function testDoSignature() {
        $key = "123";
        $secret = "abc";
        $auth = new Auth($key, $secret);
        $data = ["name" => "Jack"];
        $ts = time();
        $signature = $auth->doSignature($data, $ts);
        $this->assertStringStartsWith($key ,$signature);
        $this->assertStringStartsWith($key . "." ,$signature);
    }

    public function testConstruct() {
        $key = "123";
        $secret = "abc";
        $auth = new Auth($key, $secret);
        $this->assertEquals($auth->host, DEFAULT_HOST);

        $customHost = "http://127.0.0.1:8000/api";
        $auth = new Auth($key, $secret, $customHost);
        $this->assertEquals($customHost, $auth->host);
    }
}
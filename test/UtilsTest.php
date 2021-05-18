<?php
/**
 * Created by Kane
 * Date: 2021/5/18
 */

namespace datatom\test;


use datatom\casTools\Auth;
use datatom\casTools\CasToken;
use datatom\casTools\CasURP;
use datatom\casTools\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase{

    function testStartsWith() {
        $this->assertTrue(Utils::startsWith("abc", "a"));
        $this->assertTrue(Utils::startsWith("abc", "abc"));
        $this->assertFalse(Utils::startsWith("abc", "abcd"));
        $this->assertFalse(Utils::startsWith("abc", "b"));
        $this->assertFalse(Utils::startsWith("abc", "A"));
        $this->assertTrue(Utils::startsWith("A", "A"));
    }

    function testUrlJoin() {
        $base = "http://www.127.0.0.1:8000/cn/index.html";
        $rel = "/home.html";
        $except = "http://www.127.0.0.1:8000/home.html";
        $this->assertEquals($except, Utils::urlJoin($base, $rel));

        $rel = "home.html";
        $except = "http://www.127.0.0.1:8000/cn/home.html";
        $this->assertEquals($except, Utils::urlJoin($base, $rel));

        $rel = "home.html?keyword=123123";
        $except = "http://www.127.0.0.1:8000/cn/home.html?keyword=123123";
        $this->assertEquals($except, Utils::urlJoin($base, $rel));

        $base = "http://www.127.0.0.1:8000/cn/";
        $rel = "/home.html";
        $except = "http://www.127.0.0.1:8000/home.html";
        $this->assertEquals($except, Utils::urlJoin($base, $rel));

        $base = "http://www.127.0.0.1:8000/cn";
        $except = "http://www.127.0.0.1:8000/home.html";
        $this->assertEquals($except, Utils::urlJoin($base, $rel));

        $rel = "home.html";
        $except = "http://www.127.0.0.1:8000/home.html";
        $this->assertEquals($except, Utils::urlJoin($base, $rel));
    }

    function testMemoryUsage() {
        $ret = Utils::memoryUsage();
        $this->assertStringContainsString("/", $ret);
    }

    function testA() {
        $key = "Ncgimi5xj7sFaX1sBLlOUfGZdNd5u4IDvDIj23I1DPg";
        $secret = "Hiw3FChphDRAr6tGXDFElcxM3j8GFnyP9fgpdjApvjI";

        // 默认host="http://127.0.0.1:8000/api/"，这可以自己指定
        $host = "http://192.168.60.58:8000/api/";
        $auth = new Auth($key, $secret, $host);

        $casToken = new CasURP($auth);
        $roles = [];
        $ret = $casToken->sync($roles);
        var_dump($ret);
    }




}
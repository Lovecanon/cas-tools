<?php
/**
 * Created by Kane
 * Date: 2020/10/30
 */

namespace datatom\casTools;

use function hash;
use function base64_encode;
use function hash_hmac;
use function json_encode;

/**
 * 考虑Auth对象可以在CasURP、CasToken中复用，所以：
 * 1、将Auth对象通过构造方法传入CasURP、CasToken中；
 * 2、将access_key、secret_key、host几个配置通过Auth对象保存；
 */
class Auth {
    private $access_key;
    private $secret_key;
    public $host;

    function __construct(string $access_key, string $secret_key, string $host=null) {
        $this->access_key = $access_key;
        $this->secret_key = $secret_key;
        $this->host = $host == null ? DEFAULT_HOST : $host;
    }

    // 生成签名
    function doSignature(array $core_data, int $ts, string $method = "POST"): string {
        $data_hash = hash("md5", json_encode($core_data, JSON_UNESCAPED_SLASHES)); //hash加密验证
        $hash_data = $method . "\n" . $ts . "\n" . $data_hash; // 拼接信息
        $signature = base64_encode(hash_hmac("sha1", $hash_data, $this->secret_key, false));
        return $this->access_key . "." . $signature;
    }
}

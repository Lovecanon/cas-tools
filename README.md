cas-tools - synchronize data with DTCAS
=======================================

这个工具为开发者提供简单的方法，用以与DTCAS应用同步数据，具体加密方式参考：[dtcas-同步接口.md](http://192.168.50.2/itbasic/docs/blob/master/development/dtcas/dtcas-%E5%90%8C%E6%AD%A5%E6%8E%A5%E5%8F%A3.md)

Install
-------

To install with composer:

```sh
composer require datatom/cas-tools
```

Requires PHP 7.4 or newer.

Usage
-----

Here's a basic usage example:


```php
// verify token
$key = "Ncgimi5xj7sFaX1sBLlOUfGZdNd5u4IDvDIj23I1DPg";
$secret = "Hiw3FChphDRAr6tGXDFElcxM3j8GFnyP9fgpdjApvjI";

// 默认host="http://127.0.0.1:8000/api/"，这可以自己指定
$host = "http://192.168.60.58:8000/api/";
$auth = new Auth($key, $secret, $host);

$casToken = new CasToken($auth);
$ret = $casToken->verifyToken("aff47aa0fcee40558291228ff7fd904d");

// synchronize data with DTCAS
$key = "Ncgimi5xj7sFaX1sBLlOUfGZdNd5u4IDvDIj23I1DPg";
$secret = "Hiw3FChphDRAr6tGXDFElcxM3j8GFnyP9fgpdjApvjI";
$host = "http://192.168.60.58:8000/api/";
$auth = new Auth($key, $secret, $host);
$casToken = new CasURP($auth);
$roles = [];
$ret = $casToken->sync($roles);
```

# NOTES
### Composer中引入静态变量(文件)
在`src/DefaultConfig.php`文件中定义静态变量，在`src/Auth.php`文件中使用静态变量
```php
// src/DefaultConfig.php
const DEFAULT_HOST = "http://127.0.0.1:8000";
const DEFAULT_LOG_FILE = "/opt/logs/cas-tools.log";
```
##### 解决办法：修改`composer.json`文件
修改`composer.json`文件，添加`files`文件；

```json
  "autoload": {
    "psr-4": {
      "datatom\\casTools\\": "src/"
    },
    "files": ["src/DefaultConfig.php"]
  },
```

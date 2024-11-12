<?php

//composer require overtrue/easywechat
return [
    'app_id' => 'wxb3a8de5fcddbeecd',
//    'app_id' => 'wx02000532f09c5852',
    'secret' => '7635d2ddb3c72a7b84eafef3622c3e3c',
//    'secret' => '5c06c9943e7bd10ddfcddcb7446c1038',
//    'token' => '',
//    'aes_key' => '',  // 可选，若使用加密消息
    'log' => [
        'level' => 'debug',
        'file' => __DIR__ . '/easywechat.log',  // 日志文件路径
    ],
    'http' => [
        'timeout' => 5.0,
        'connect_timeout' => 5.0,
    ],
];

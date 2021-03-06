<?php
/**
 * 服务入口脚本
 * @author Biscuit\@Linux
 */

use Frame\SimpleNamespaceRouter;

require_once __DIR__.'/vendor/autoload.php';

//监听地址
$addr = '0.0.0.0';
//监听端口号
$port = 9501;

$http = new swoole_http_server($addr, $port);
echo 'HTTP Server listen at '.$addr.':'.$port.PHP_EOL;

$http->set(array(
    'worker_num' => 4,      //Worker子进程数量
    'max_request' => 5000,  //Worker重启前最多处理的请求数，防止内存泄漏
    'package_max_length' => 20*1024*1024,   //影响最大文件上传大小
    'upload_tmp_dir' => __DIR__.'/tmp/',    //文件上传临时目录
));

//每个Worker进程启动时执行，执行进程初始化
$http->on('WorkerStart', function ($serv, $worker_id){
    global $manager;
    $manager = new Frame\Manager(new SimpleNamespaceRouter(),array(new \Frame\Log\LogPlugin()));
    echo 'Worker started: '.getmypid().PHP_EOL;
});

//每个Worker进程结束时执行
$http->on('WorkerStop', function ($serv, $worker_id){
    echo 'Worker stoped: '.getmypid().PHP_EOL;
});

//每次HTTP请求时执行
$http->on('request', function ($request, $response) {
    global $manager;
    $manager->doRequest($request, $response);
});

$http->start();
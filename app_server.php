<?php
/**
 * 服务入口脚本
 * @author Biscuit\@Linux
 */

use Frame\SimpleNamespaceRouter;
use Plugin\SamplePlugin;

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
));

//每个Worker进程启动时执行，执行进程初始化
$http->on('WorkerStart', function ($serv, $worker_id){
    global $manager;
    $manager = new Frame\Manager(new SimpleNamespaceRouter(),array(/*new SamplePlugin()*/));
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
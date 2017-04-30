<?php
namespace Plugin;

use \Swoole\Http\Request;
use \Swoole\Http\Response;

/**
 * 插件例子
 * @author Biscuit\@Linux
 */
class SamplePlugin extends \Frame\BasePlugin {
    public function beforeAction(Request $request, Response $response){
        echo 'before'.PHP_EOL;
    }
    
    public function afterAction(Request $request, Response $response){
        echo 'after'.PHP_EOL;
    }
}
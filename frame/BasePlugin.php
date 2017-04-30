<?php
namespace Frame;

use \Swoole\Http\Request;
use \Swoole\Http\Response;

/**
 * Web框架插件，类似于Java Servlet的Filter
 * @author Biscuit\@Linux
 */
class BasePlugin implements PluginInterface {
    /**
     * @{inheritDoc}
     */
    public function beforeAction(Request $request, Response $response){
    }
    
    /**
     * @{inheritDoc}
     */
    public function afterAction(Request $request, Response $response){
    }
}
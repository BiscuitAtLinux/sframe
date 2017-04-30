<?php
namespace Frame;

use \Swoole\Http\Request;
use \Swoole\Http\Response;

/**
 * Web框架插件，类似于Java Servlet的Filter
 * @author Biscuit\@Linux
 */
interface PluginInterface {
    /**
     * 执行实际Action之前执行
     * @param Request $request
     * @param Response $response
     */
    public function beforeAction(Request $request, Response $response);
    
    /**
     * 实际Action执行之后执行
     * @param Request $request
     * @param Response $response
     */
    public function afterAction(Request $request, Response $response);
}
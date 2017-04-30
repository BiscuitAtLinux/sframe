<?php
namespace Frame;

use \Swoole\Http\Request;
use \Swoole\Http\Response;

/**
 * Web框架管理器，应用入口
 * @author Biscuit\@Linux
 */
class Manager {
    
    /** @var RouterInterface $router */
    private $router = null;
    private $pluginList = null;
    
    /**
     * Web框架管理器
     * @param RouterInterface $router 路由类的实例
     * @param array $pluginList 插件实例列表
     */
    public function __construct(RouterInterface $router, array $pluginList = array()) {
        $this->router = $router;
        $this->pluginList = $pluginList;
    }
    
    /**
     * 处理请求
     * 执行插件钩子，执行路由
     * @param Request $request
     * @param Response $response
     */
    public function doRequest(Request $request, Response $response) {
        try {
            $httpEnd = false;
            //执行插件beforeAction
            foreach ($this->pluginList as /** @var PluginInterface $plugin*/ $plugin) {
                $plugin->beforeAction($request, $response);
            }
            //由路由执行实际请求
            $this->router->doRequest($request, $response);
            $httpEnd = true;
            //执行插件aftereAction
            foreach ($this->pluginList as /** @var PluginInterface $plugin*/ $plugin) {
                $plugin->afterAction($request, $response);
            }
        } catch (\Exception $e) {
            echo $e->getTraceAsString().PHP_EOL;
            //如果HTTP请求还没有结束，就可以报500
            if (!$httpEnd) {
                $response->status(500);
                $response->end('Server Error: ' . $e->getMessage());
            }
        }
    }
}
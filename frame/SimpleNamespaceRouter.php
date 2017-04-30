<?php
namespace Frame;

use \Swoole\Http\Request;
use \Swoole\Http\Response;

/**
 * 根据访问路径path_info和命名空间去查找Action的简单路由
 * 
 * @author Biscuit\@Linux
 */
class SimpleNamespaceRouter implements RouterInterface {
    private $baseNamespace = '';
    private $indexAction = '';
    public function __construct($baseNamespace = 'Action', $indexAction = 'Index') {
        $this->baseNamespace = $baseNamespace;
        $this->indexAction = $indexAction;
    }
    
    /**
     * 执行路由，查找Action，执行Action
     * {@inheritdoc}
     *
     * @see \Frame\RouterInterface::doRequest()
     */
    public function doRequest(Request $request, Response $response) {
        // 从访问路径获得各级名称，由于以'/'开头，所以第一个元素一定为空字符串
        $pathList = explode ( '/', $request->server ['path_info'] );
        // 用根命名空间填充第一个空字符串
        $pathList [0] = $this->baseNamespace;
        // 如果最后一个元素也为空，则填充index
        if ($pathList [count ( $pathList ) - 1] === '') {
            $pathList [count ( $pathList ) - 1] = $this->indexAction;
        }
        // 首字母大写
        foreach ( $pathList as &$path ) {
            $path = ucfirst ( $path );
        }
        // 拼接Action类名
        $actionClazz = "\\" . implode ( "\\", $pathList );
        
        // 判断Action是否存在
        if (class_exists ( $actionClazz )) {
            // 创建Action实例
            $action = new $actionClazz ();
            // 调用Action
            $action->doRequest ( $request, $response );     
        } else {
            // 不存在则抛404
            $response->status ( 404 );
            $response->end ( 'Action ' . $actionClazz . ' not found.' );
        }
    }
}
<?php
namespace Frame;

use \Swoole\Http\Request;
use \Swoole\Http\Response;

/**
 * Action基类，用于实际处理用户的一次请求
 * @author Biscuit\@Linux
 */
class BaseAction implements ActionInterface {
    /**
     * {@inheritDoc}
     * @see \Frame\ActionInterface::doRequest()
     */
    public function doRequest(Request $request, Response $response) {
        $response->end('This is BaseAction, please override me');
    }
    
    /**
     * 获取参数
     * @param Request $request
     * @param string $key 参数key，不传则返回所有参数
     * @return mixed
     */
    public static function getParam(Request $request, $key=null) {
        //merge POST和GET的参数，GET参数优先级高
        if (isset($request->post) && isset($request->get)) {
            $params = array_merge($request->post,$request->get);
        } else if (isset($request->get)) {
            $params = $request->get;
        } else if (isset($request->post)) {
            $params = $request->post;
        } else {
            $params = array();
        }
        
        if ($key) {
            return $params[$key];
        } else {
            return $params;
        }
    }
    
    /**
     * 向客户端以JSON格式发送数据
     * @param Response $response
     * @param mixed $data
     */
    public static function renderJson(Response $response, $data) {
        $response->header('Content-type', 'application/json');
        self::logOutput($data);
        $response->end(json_encode($data));
    }
    
    /**
     * 记录输出日志
     * @param mixed $output
     */
    public static function logOutput($output) {
        \Frame\Log\LogPlugin::addData('output', $output);
    }
}
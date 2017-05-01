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
        if (isset($request->post)) {
            $params = array_merge($request->post,$request->get);
        } else {
            $params = $request->get;
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
     * @param any $data
     */
    public static function renderJson(Response $response, $data) {
        $response->header('Content-type', 'application/json');
        $response->end(json_encode($data));
    }
}
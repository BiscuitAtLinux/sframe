<?php
namespace Frame;
use \Swoole\Http\Request;
use \Swoole\Http\Response;

/**
 * 路由接口，查找并执行实际的Action
 * @author Biscuit\@Linux
 */
interface RouterInterface {
    public function doRequest(Request $request, Response $response);
}
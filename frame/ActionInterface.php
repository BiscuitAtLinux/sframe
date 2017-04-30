<?php
namespace Frame;

use \Swoole\Http\Request;
use \Swoole\Http\Response;

/**
 * Action接口，用于实际处理用户的一次请求
 * @author Biscuit\@Linux
 */
interface ActionInterface {
    /**
     * 处理用户请求，返回结果
     * @param Request $request
     * @param Response $response
     */
    public function doRequest(Request $request, Response $response);
}
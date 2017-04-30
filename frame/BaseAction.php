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
}
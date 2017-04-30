<?php
namespace Action;

use \Swoole\Http\Request;
use \Swoole\Http\Response;

class Index extends \Frame\BaseAction{
    public function doRequest(Request $request, Response $response) {
        $response->end('Hello Index Action');
    }
}
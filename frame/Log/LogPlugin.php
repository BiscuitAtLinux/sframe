<?php
namespace Frame\Log;

use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * 简单日志插件，每次请求自动打印一次NOTICE日志
 * @author biscuit
 */
class LogPlugin extends \Frame\BasePlugin {
    
    private $logger = null;
    private static $slogger = null;
    private $startTime = null;
    private static $extraData = null;   //日志额外数据
    
    /**
     * 构造函数
     * @param SimpleLogger $logger 日志实例
     */
    public function __construct($logger=null) {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new SimpleLogger();
        }
        //记录到静态变量中，便于Action中访问
        self::$slogger = $this->logger;
    }
    
    /**
     * 进入Action前记录请求开始时间
     * @{inheritDoc}
     */
    public function beforeAction(Request $request, Response $response){
        $this->startTime = microtime(true);
    }
    
    /**
     * Action结束后打印日志
     * @{inheritDoc}
     */
    public function afterAction(Request $request, Response $response){
        $endTime = microtime(true);
        $this->logger->notice(sprintf('remote=%s(%s) request_uri=%s%s ua="%s" start=%0.3f end=%0.3f const=%0.3f param=%s cookie=%s extra=%s',
                $request->server['remote_addr'],isset($request->header['x-forwarded-for'])?$request->header['x-forwarded-for']:'-', 
                $request->server['request_uri'],isset($request->server['query_string'])?'?'.$request->server['query_string']:'', 
                $request->header['user-agent'], $this->startTime,$endTime, $endTime-$this->startTime,
                json_encode(self::getParam($request)),json_encode($request->cookie),json_encode(self::$extraData)));
        $this->startTime = null;
        self::$extraData = null;
    }
    
    /**
     * 获取参数
     * @param Request $request
     * @return mixed
     */
    private static function getParam(Request $request) {
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
    
        return $params;
    }
    
    /**
     * 获得日志对象
     * @return \Frame\Log\SimpleLogger
     */
    public static function getLogger() {
        return self::$slogger;
    }
    
    /**
     * 添加访问日志中的参数
     * @param string $key
     * @param mixed $value
     */
    public static function addData($key,$value) {
        if (self::$extraData === null) {
            self::$extraData = array();
        }
        self::$extraData[$key] = $value;
    }
}
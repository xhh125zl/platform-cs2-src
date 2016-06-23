<?php
namespace common\controller;
use base;
use safe;
define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['ajax']) || !empty($_GET['ajax'])) ? true : false);
class commonController {
	/**
     * 视图实例对象
     * @var view
     * @access protected
     */    
    protected $view = null;
	protected $input = null;
    public $_site_url = '';//网站域名
    public $_module = '';
    public $_controller = '';//当前控制器
    public $_action = '';//当前操作
    public $_timestamp = '';
    protected function _initialize() {
        $this->view = base\base::instance('base\view');
        $this->_site_url = SITE_URL;
        $this->_module = base\dispatcherUrl::getModule();
        $this->_controller = base\dispatcherUrl::getController();
        $this->_action = base\dispatcherUrl::getAction();
		if($this->_module == 'shop'){
			$this->input = base\base::instance('safe\input');
			$_GET = $this->input->getVar($_GET);
			$_POST = $this->input->getVar($_POST);
			$_COOKIE = $this->input->getVar($_COOKIE);
			$this->safe();
		}
        $this->_timestamp = TIMESTAMP;
        $this->assign('_site_url', $this->_site_url);
        $this->assign('_controller', $this->_controller);
        $this->assign('_action', $this->_action);
    }
	
	protected function assign($name, $value = '') {
        $this->view->assign($name, $value);
    }
	
    protected function display($templateFile = '', $templateDir = '', $layout = 'null_layout') {
		$this->view->_layout_file = APP_PATH . '/modules/' . base\dispatcherUrl::getModule() . '/view/layout/' . $layout . '.php';
		if(strpos($templateDir, '@') !== false) {
			$this->view->_tpl_dir = APP_PATH . '/modules/' . base\dispatcherUrl::getModule() . '/view';
		}elseif($templateDir){
			$this->view->_tpl_dir = APP_PATH . '/modules/' . base\dispatcherUrl::getModule() . '/view/' . $templateDir;
		}else {
			$this->view->_tpl_dir = FRAMEWORK_PATH . '/views';
		}
		$this->assign('tpl_file', $this->view->_tpl_dir . '/' .$templateFile);
        $this->view->display($templateFile);
    }
	
	protected function error($message, $jumpUrl = '') {
		$this->dispatchJump($message, $jumpUrl, $status = 0);
    }
	
	protected function success($message, $jumpUrl = '') {
		$this->dispatchJump($message, $jumpUrl, $status = 1);
    }
    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为框架目录下面的msg_jump.php页面
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
	 * @param Boolean $status 状态
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @access protected
     * @return void
     */
    protected function dispatchJump($message, $jumpUrl = '', $status = 1, $ajax = false) {
        if(true === $ajax || IS_AJAX) {// AJAX提交
            $data = is_array($ajax) ? $ajax : array();
            $data['info'] = $message;
            $data['status'] = $status;
            $data['url'] = $jumpUrl;
            $this->ajaxReturn($data);
        }
        if(is_int($ajax)) $this->assign('waitSecond', $ajax);
        if(!empty($jumpUrl)) $this->assign('jumpUrl', $jumpUrl);
        // 提示标题
        $this->assign('msgTitle', $status ? '操作成功' : '操作失败');
        $this->assign('status', $status);   // 状态
        if($status) { //发送成功信息
            $this->assign('message', $message);// 提示信息
            // 成功操作后默认停留1秒
            if(!isset($this->waitSecond)) $this->assign('waitSecond', '1');
            // 默认操作成功自动返回操作前页面
            if(empty($jumpUrl)) $this->assign('jumpUrl', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
			$this->display('msg_jump.php');
        }else {
            $this->assign('error', $message);// 提示信息
            //发生错误时候默认停留3秒
            if(!isset($this->waitSecond)) $this->assign('waitSecond', '3');
            // 默认发生错误的话自动返回上页
            if(empty($jumpUrl)) $this->assign('jumpUrl', 'javascript:history.back(-1);');
			$this->display('msg_jump.php');
            // 中止执行  避免出错后继续执行
        }
		exit;
    }
	/**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    protected function ajaxReturn($data, $type = '', $json_option = 0) {
        if(empty($type)) $type = 'JSON';
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                //header('Content-Type:application/json; charset=utf-8');
                header('Content-Type:text/html; charset=utf-8');
                exit(json_encode($data, $json_option));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler = isset($_GET['callback']) ? $_GET['callback'] : 'jsonpReturn';
                exit($handler.'('.json_encode($data, $json_option).');');  
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
        }
    }
	/**
     * 魔术方法 有不存在的操作的时候执行
     * @access protected
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method, $args) {
        if(0 === strcasecmp($method, $this->_action)) {
            if(method_exists($this, '_empty')) {
                // 如果定义了_empty操作 则调用
                $this->_empty($method, $args);
            }else {
				throw new \Exception('非法操作：'.$this->_action);
            }
        }else {
			throw new \Exception(__CLASS__ .':'.$method.'方法不存在！');
            return;
        }
    }
	/* 空操作，用于输出404页面 */
    protected function _empty() {
        header('HTTP/1.1 404 Not Found');
        header('Status:404 Not Found');
		header('Location:' . SITE_URL . '/404.php');
    }
	private function safe(){
	    $getfilter = "'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
		$postfilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
		$cookiefilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
		foreach($_GET as $key => $value){ 
			$this->StopAttack($key, $value, $getfilter);
		}
		foreach($_POST as $key => $value){ 
			$this->StopAttack($key, $value, $postfilter);
		}
		foreach($_COOKIE as $key => $value){ 
			$this->StopAttack($key, $value, $cookiefilter);
		}
	}
	private function StopAttack($StrFiltKey, $StrFiltValue, $ArrFiltReq) {  
		if(is_array($StrFiltValue)) {
			$StrFiltValue = implode($StrFiltValue);
		}
		if(preg_match('/' . $ArrFiltReq . '/is', $StrFiltValue) == 1) {
		    $logs = '<br><br>操作IP: ' . $_SERVER['REMOTE_ADDR'] . '<br>操作时间: ' . strftime('%Y-%m-%d %H:%M:%S') . '<br>操作页面:' . $_SERVER['PHP_SELF'] . '<br>提交方式: ' . $_SERVER['REQUEST_METHOD'] . '<br>提交参数: ' . $StrFiltKey . '<br>提交数据: ' . $StrFiltValue;
			$toppath = SITE_PATH . '/pc/log.htm';
			$Ts = fopen($toppath, 'a+');
			fputs($Ts, $logs);
			fclose($Ts);
			$this->error('不安全操作');
		}      
    } 
}
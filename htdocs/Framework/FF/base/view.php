<?php
namespace base;
/**
 * 模板驱动，模板引擎
 *
 **/
class view {
	/**
	 * 输出模板内容的数组，其他的变量不允许从程序中直接输出到模板
	 */
	private $_output_value = array();
	/**
	 * 模板路径设置
	 */
	public $_tpl_dir = '';
	/**
	 * 默认layout
	 */
	public $_layout_file = '';
	/**
	 * 是否开启缓存
	 */
	public $caching = false;
	/**
	 * 缓存文件
	 */
	public $cache_dir = 'cache';
	/**
	 * 缓存文件过期时间
	 */
	public $cache_time = 3600;
	
	/**
	 * 抛出变量
	 *
	 * @param mixed $output
	 * @param  void
	 */
	public function assign($output, $input = '') {
		$this->_output_value[$output] = $input;
	}
	
	/**
	 * 调用显示模板 支持html页面缓存
	 *
	 * @param string $page_name
	 * @param string $layout
	 * @param string $app
	 */
	public function display($page_name) {
		if (!empty($this->_tpl_dir)) {
			$_tpl_dir = $this->_tpl_dir.'/';
		}else {
			$_tpl_dir = '';
		}
		//对模板变量进行赋值
		$output = $this->_output_value;
		
		//判断是否使用布局方式输出模板，如果是，那么包含布局文件，并且在布局文件中包含模板文件
		if (!empty($this->_layout_file)) {
			$tpl_file = $this->_layout_file;
		}else {
			$tpl_file = $_tpl_dir.$page_name;
		}
		
		if (file_exists($tpl_file)) {
			if ($this->caching) {
				//缓存文件
				$cache_name = $_SERVER['HTTP_HOST'] . (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '');//控制器判断及过滤一定要给力~不放过任何非法页面
				$cache_file = $this->cache_dir.'/'.md5($cache_name).'.html';
				//重新生成缓存文件
				if (!file_exists($cache_file) || filemtime($cache_file) < filemtime($tpl_file)) {
					if (time() > filemtime($cache_file) + $this->cache_time) {
						ob_start();
						ob_implicit_flush(false);
						//引入模板文件
						include($tpl_file);
						//缓存内容
						$_content = ob_get_clean();
						//生成缓存文件
						//如果有HTTP 4xx 3xx 5xx 头部，禁止存储
						//对注入的网址 防止生成，例如 /game/lst/SortType/hot/-e8-90-8c-e5-85-94-e7-88-b1-e6-b6-88-e9-99-a4/-e8-bf-9b-e5-87-bb-e7-9a-84-e9-83-a8-e8-90-bd/-e9-a3-8e-e4-ba-91-e5-a4-a9-e4-b8-8b/index.shtml
						if (!preg_match('/Status.*[345]{1}\d{2}/i', implode(' ', headers_list())) && !preg_match('/(-[a-z0-9]{2}){3,}/i', $cache_file)) {
							if (FALSE === file_put_contents($cache_file, $_content)) {
								throw new \Exception('缓存文件生成出错！');
							}
						}else {
							throw new \Exception('禁止存储，文件存在异常！');
						}
					}
				}
				//载入缓存文件
				include_once($cache_file);
			}else {
				include_once($tpl_file);
			}
		}else {
			throw new \Exception('view error:'.$tpl_file.' is not exists');
		}
	}
	/**
	 * 显示页面Trace信息
	 *
	 * @return array
	 */
    public function showTrace(){
    	$trace = array();
    	//当前页面
		$trace['当前页面'] =  $_SERVER['REQUEST_URI'].'<br/>';
    	//请求时间
        $trace['请求时间'] =  date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']).'<br/>';
        //系统运行时间
        $query_time = number_format((microtime(true)-StartTime),3).'s';
        $trace['页面执行时间'] = $query_time.'<br>';
		//内存
		$trace['占用内存'] = number_format(memory_get_usage()/1024/1024,2).'MB'.'<br/>';
		//请求方法
        $trace['请求方法'] = $_SERVER['REQUEST_METHOD'].'<br/>';
        //通信协议
        $trace['通信协议'] = $_SERVER['SERVER_PROTOCOL'].'<br/>';
        //用户代理
        $trace['用户代理'] = $_SERVER['HTTP_USER_AGENT'].'<br/>';
        //会话ID
        $trace['会话ID'] = session_id().'<br/>';
        //执行日志
        $log = Log::read();
        $trace['日志记录'] = count($log).'条日志'.'<br/>'.implode('<br/>', $log);
        //文件加载
		$files = get_included_files();
		$trace['加载文件'] = count($files).str_replace("\n", '<br/>', substr(substr(print_r($files,true),7),0,-2)).'<br/>';
        return $trace;
    }
}

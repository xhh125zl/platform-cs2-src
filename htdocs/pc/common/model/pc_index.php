<?php
namespace common\model;
class pc_index extends \base\model{
	/**
	 * 读取记录列表
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getWebList($condition = array('web_page' => 'index')){
		$result = $this->where($condition)->order('web_sort')->select();
		return $result;
	}

	/**
	 * 更新模块信息
	 *
	 * @param
	 * @return bool 布尔类型的返回结果
	 */
	public function updateWeb($condition,$data){
		$web_id = $condition['web_id'];
		if (intval($web_id) < 1){
			return false;
		}
		if (is_array($data)){
			$result = $this->where($condition)->update($data);
			return $result;
		} else {
			return false;
		}
	}
	/**
	 * 模块html信息
	 *
	 */
	public function getWebHtml($web_page = 'index',$UsersID){
		$web_array[$web_page] = '';
		$web_list = $this->getWebList(array('Users_ID'=>$UsersID,'web_show'=>1,'web_page'=>$web_page));
		if(!empty($web_list) && is_array($web_list)) {
			foreach($web_list as $k => $v){
			    $key = $v['web_page'];
				if (!empty($v['web_html'])) {
					$web_array[$key] .= $v['web_html'];
				}
			}
		}
		return $web_array;
	}

	/**
	 * 转换字符串
	 */
	public function get_array($code_info,$code_type){
		$data = '';
		switch ($code_type) {
    	    case "array":
    	    	if(is_string($code_info)) $code_info = unserialize($code_info);
    	    	if(!is_array($code_info)) $code_info = array();
    	    	$data = $code_info;
    	      break;
    	    case "html":
    	    	if(!is_string($code_info)) $code_info = '';
    	    	$data = $code_info;
    	    	break;
    	    default:
    	    	$data = '';
    	    	break;
		}
		return $data;
	}

	/**
	 * 转换数组
	 */
	public function get_str($code_info,$code_type){
		$str = '';
		switch ($code_type) {
    	    case "array":
    	    	if(!is_array($code_info)) $code_info = array();
    	    	$code_info = $this->stripslashes_deep($code_info);
    	    	$str = serialize($code_info);
    	    	$str = addslashes($str);
    	      break;
    	    case "html":
    	    	if(!is_string($code_info)) $code_info = '';
    	    	$str = $code_info;
    	    	break;
    	    default:
    	    	$str = '';
    	    	break;
		}
		return $str;
	}
	/**
	 * 递归去斜线
	 */
	public function stripslashes_deep($value){
		$value = is_array($value) ? array_map(array($this,'stripslashes_deep'), $value) : stripslashes($value);
		return $value;
	}

	/**
	 * 更新商品价格信息
	 *
	 */
	public function updateWebGoods($condition = array('web_show' => '1')){
		$web_style_array = array();
		$web_list = $this->getWebList($condition);//板块列表
		if(!empty($web_list) && is_array($web_list)) {
			foreach($web_list as $k => $v){
			    $web_id = $v['web_id'];
				$web_style_array[$web_id] = $v['style_name'];
			}
			$goods_ids = array();//商品ID数组
	        $condition = array();
	        $condition['web_id'] = array('in', array_keys($web_style_array));
	        $condition['var_name'] = array('in', array('recommend_list','sale_list'));
			$code_list = $this->getCodeList($condition);//有商品内容记录列表
			if(!empty($code_list) && is_array($code_list)) {
			    $update_list = array();
    			foreach ($code_list as $key => $val) {
    				$code_id = $val['code_id'];
    				$code_info = $val['code_info'];
    				$code_type = $val['code_type'];
    				$val['code_info'] = $this->get_array($code_info,$code_type);//输出变量数组
        	        $recommend_list = $val['code_info'];
        	        if (!empty($recommend_list) && is_array($recommend_list)) {
        	            foreach ($recommend_list as $k => $v) {
        	                if (!empty($v['goods_list']) && is_array($v['goods_list'])) {//商品列表
        	                    $goods_id_array = array_keys($v['goods_list']);//商品ID
        	                    $goods_ids = array_merge($goods_ids, $goods_id_array);
        	                    $update_list[$code_id] = $val;
        	                }
        	            }
        	        }
    			}
    			if (!empty($goods_ids) && is_array($goods_ids)) {
    			    $condition = array();
    			    $condition['goods_id'] = array('in', $goods_ids);
    			    $goods_list = $this->getGoodsList($condition);//最新商品
    			}
    			foreach ($update_list as $key => $val) {
    				$update = 0;//商品价格是否有变化
        	        foreach ($val['code_info'] as $k => $v) {
        	            if (!empty($v['goods_list']) && is_array($v['goods_list'])) {
            	            foreach ($v['goods_list'] as $k3 => $v3) {//单个商品
            	                $goods_id = $v3['goods_id'];
            	                $goods_price = $v3['goods_price'];
            	                if (!empty($goods_list[$goods_id]) && ($goods_list[$goods_id]['goods_price'] != $goods_price)) {
            	                    $val['code_info'][$k]['goods_list'][$goods_id]['goods_price'] = $goods_list[$goods_id]['goods_price'];
            	                    $update++;
            	                }
            	            }
        	            }
        	        }
        	        if ($update > 0) {//更新对应内容
        				$code_id = $val['code_id'];
        				$web_id = $val['web_id'];
        	            $code_type = $val['code_type'];
        	            $code_info = $this->get_str($val['code_info'],$code_type);
        	            $this->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));
        	            $this->updateWebHtml($web_id,$web_style_array[$web_id]);
        	        }
    			}
			}
		}
	}

}

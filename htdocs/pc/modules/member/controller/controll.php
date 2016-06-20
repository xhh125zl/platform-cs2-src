<?php
namespace member\controller;
use base;
class controllController extends \common\controller\memberController {
	public function __construct() {
		parent::_initialize();
	}
	function getTreeClassList($show_deep = '2', $condition = array()){
		$condition['Users_ID'] = $_SESSION['Users_ID'];
        $class_list = $this->getGoodsClassList($condition);
        $goods_class = array();//分类数组
        if(is_array($class_list) && !empty($class_list)) {
            $show_deep = intval($show_deep);
            if ($show_deep == 1){//只显示第一级时用循环给分类加上深度deep号码
                foreach ($class_list as $val) {
                    if($val['Category_ParentID'] == 0) {
                        $val['deep'] = 1;
                        $goods_class[] = $val;
                    } else {
                        break;//父类编号不为0时退出循环
                    }
                }
            } else {//显示第二和三级时用递归
                $goods_class = $this->_getTreeClassList($show_deep,$class_list);
            }
        }
        return $goods_class;
    }
	private function _getTreeClassList($show_deep, $class_list, $deep = 1, $parent_id = 0, $i = 0){
        static $show_class = array();//树状的平行数组
        if(is_array($class_list) && !empty($class_list)) {
            $size = count($class_list);
            if($i == 0) $show_class = array();//从0开始时清空数组，防止多次调用后出现重复
            for ($i;$i < $size;$i++) {//$i为上次循环到的分类编号，避免重新从第一条开始
                $val = $class_list[$i];
                $gc_id = $val['Category_ID'];
                $gc_parent_id	= $val['Category_ParentID'];
                if($gc_parent_id == $parent_id) {
                    $val['deep'] = $deep;
                    $show_class[] = $val;
                    if($deep < $show_deep && $deep < 2) {//本次深度小于显示深度时执行，避免取出的数据无用
                        $this->_getTreeClassList($show_deep, $class_list, $deep+1, $gc_id, $i+1);
                    }
                }
                if($gc_parent_id > $parent_id) break;//当前分类的父编号大于本次递归的时退出循环
            }
        }
        return $show_class;
    }
	function getGoodsClassList($condition, $field = '*') {
        $result = model('shop_category')->field($field)->where($condition)->order('Category_ParentID asc,Category_Index asc,Category_ID asc')->select();
		return $result;
    }
	/**
	 * 转换字符串
	 */
	public function get_array($code_info, $code_type){
		$data = '';
		switch ($code_type) {
    	    case "array":
    	    	if(is_string($code_info)) $code_info = unserialize(htmlspecialchars_decode($code_info));
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
}
?>
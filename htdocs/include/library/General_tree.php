<?php
/**
 * Tree 树型类(无限分类)
 *
 * @author Kvoid
 * @copyright http://kvoid.com
 * @version 1.0
 * @access public
 * @example
 *   $tree= new Tree($result);
 *   $arr=$tree->leaf(0);
 *   $nav=$tree->navi(15);
 */
class General_tree {
    private $result;
    private $tmp;
    private $arr;
    private $already = array();
    /**
     * 构造函数
     *
     * @param array $result 树型数据表结果集
     * @param array $fields 树型数据表字段，array(分类id,父id)
     * @param integer $root 顶级分类的父id
     */
    public function __construct($param) {
		
        $this->result = $param['result'];
		$this->fields = isset($param['fields'])?$param['fields']:array('id','pid');
		$this->root =  isset($param['root'])?$param['root']:0;
		
		$this->handler();
		
    }
    /**
     * 树型数据表结果集处理
     */
    private function handler() {
	
        foreach ($this->result as $node) {
            $tmp[$node[$this->fields[1]]][] = $node;
        }
		
        krsort($tmp);
        for ($i = count($tmp); $i > 0; $i--) {
            foreach ($tmp as $k => $v) {
                if (!in_array($k, $this->already)) {
                    if (!$this->tmp) {
                        $this->tmp = array($k, $v);
                        $this->already[] = $k;
                        continue;
                    } else {
                        foreach ($v as $key => $value) {
                            if ($value[$this->fields[0]] == $this->tmp[0]) {
                                $tmp[$k][$key]['child'] = $this->tmp[1];
                                $this->tmp = array($k, $tmp[$k]);
                            }
                        }
                    }
                }
            }
            $this->tmp = null;
        }
        $this->tmp = $tmp;
    }
    /**
     * 反向递归
     */
    private function recur_n($arr, $id) {
        foreach ($arr as $v) {
            if ($v[$this->fields[0]] == $id) {
                $this->arr[] = $v;
                if ($v[$this->fields[1]] != $this->root) $this->recur_n($arr, $v[$this->fields[1]]);
            }
        }
    }
    /**
     * 正向递归
     */
    private function recur_p($arr) {
        foreach ($arr as $v) {
            $this->arr[] = $v[$this->fields[0]];
            if (isset($v['child'])) $this->recur_p($v['child']);
        }
    }
	
    /**
     * 菜单 多维数组
     *
     * @param integer $id 分类id
     * @return array 返回分支，默认返回整个树
     */
    public function leaf($id = null) {
        $id = ($id == null) ? $this->root:$id;
		if(isset($this->tmp[$id])){      
			return $this->tmp[$id];
		}else{
			return array();
		}
	}
	
    /**
     * 导航 一维数组
     *
     * @param integer $id 分类id
     * @return array 返回单线分类直到顶级分类
     */
    public function navi($id) {
        
		$this->arr = array();
        
		$this->recur_n($this->result, $id);
		if(!empty($this->arr)){	
			krsort($this->arr);
		}
		
		return $this->arr;
		
    }
    /**
     * 散落 一维数组
     *
     * @param integer $id 分类id
     * @return array 返回leaf下所有分类id
     */
    public function leafid($id) {
        $this->arr = null;
        $this->arr[] = $id;
        $this->recur_p($this->leaf($id));
        return $this->arr;
    }
}
?>

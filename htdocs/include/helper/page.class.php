<?php
/**
 * 一个用于Mysql数据库的分页类
 *
 *
 * 使用实例:
 * $p = new Page;      //建立新对像
 * $p->file="ttt.php";      //设置文件名，默认为当前页
 * $p->pvar="pagecount";    //设置页面传递的参数，默认为p
 * $p->setvar(array("a" => '1', "b" => '2'));   //设置要传递的参数,要注意的是此函数必须要在 set 前使用，否则变量传不过去
 * $p->set(20,2000,1);      //设置相关参数，共三个，分别为'页面大小'、'总记录数'、'当前页(如果为空则自动读取GET变量)'
 * $p->output(0);           //输出,为0时直接输出,否则返回一个字符串
 * echo $p->limit();        //输出Limit子句。在sql语句中用法为 "SELECT * FROM TABLE LIMIT {$p->limit()}";
 *
 */


class Page {

    /**
     * 页面输出结果
     *
     * @var string
     */
    var $output;

    /**
     * 使用该类的文件,默认为 PHP_SELF
     *
     * @var string
     */
    var $file;

    /**
     * 页数传递变量，默认为 'p'
     *
     * @var string
     */
    var $pvar = "p";

    /**
     * 页面大小
     *
     * @var integer
     */
    var $psize;

    /**
     * 当前页面
     *
     * @var ingeger
     */
    var $curr;

    /**
     * 要传递的变量数组
     *
     * @var array
     */
    var $varstr;

    /**
     * 总页数
     *
     * @var integer
     */
    var $tpage;

    /**
     * 分页设置
     *
     * @access public
     * @param int $pagesize 页面大小
     * @param int $total    总记录数
     * @param int $current  当前页数，默认会自动读取
     * @return void
     */
    function set($pagesize=20,$total,$current=false) {
        global $HTTP_SERVER_VARS,$HTTP_GET_VARS;
      
        $this->tpage = ceil($total/$pagesize);

        if (!$current) {$current = $HTTP_GET_VARS[$this->pvar];}

//        if ($current>$this->tpage) {$current = $this->tpage;}

        if ($current<1) {$current = 1;}

        $this->curr  = $current;
        $this->psize = $pagesize;
  
        if (!$this->file) {$this->file = $HTTP_SERVER_VARS['PHP_SELF'];}

        if ($this->tpage > 1) {
            //show total pages
            $this->output .= "共<b>".$this->tpage."</b>页&nbsp;<b>".$total."</b>条信息&nbsp;".$this->psize."/页&nbsp;&nbsp;";
            
            if ($current>10) {
                $this->output.='<a href='.$this->file.'?'.$this->pvar.'='.($current-10).($this->varstr).' title="前十页">[&lt;&lt;&lt;]</a>&nbsp;';
            }
            if ($current>1) {
                $this->output.='<a href='.$this->file.'?'.$this->pvar.'='.($current-1).($this->varstr).' title="前一页">[&lt;&lt;]</a>&nbsp;';
            }

            $start  = floor($current/10)*10;
            $end    = $start+9;

            if ($start<1)           {$start=1;}
            if ($end>$this->tpage)  {$end=$this->tpage;}

            for ($i=$start; $i<=$end; $i++) {
                if ($current==$i) {
                    $this->output.='<font color="red">'.$i.'</font>&nbsp;';    //输出当前页数
                } else {
                    $this->output.='<a href="'.$this->file.'?'.$this->pvar.'='.$i.$this->varstr.'">['.$i.']</a>&nbsp;';    //输出页数
                }
            }

            if ($current<$this->tpage) {
                $this->output.='<a href='.$this->file.'?'.$this->pvar.'='.($current+1).($this->varstr).' title="下一页">[&gt;&gt;]</a>&nbsp;';
            }
            if ($this->tpage>10 && ($this->tpage-$current)>=10 ) {
                $this->output.='<a href='.$this->file.'?'.$this->pvar.'='.($current+10).($this->varstr).' title="下十页">[&gt;&gt;&gt;]</a>';
            }
        }else{
            $this->output='第<font color=red>'.$current.'</font>页&nbsp;共<font color=red>'.$this->tpage.'</font>页&nbsp;<font color=red><b>'.$total.'</b></font>条信息&nbsp;'.$this->psize.'/页&nbsp;&nbsp;';
        }
        
    }

    /**
     * 要传递的变量设置
     *
     * @access public
     * @param array $data   要传递的变量，用数组来表示，参见上面的例子
     * @return void
     */ 
    function setvar($data) {
        foreach ($data as $k=>$v) {
            $this->varstr.='&amp;'.$k.'='.urlencode($v);
        }
    }

    /**
     * 分页结果输出
     *
     * @access public
     * @param bool $return 为真时返回一个字符串，否则直接输出，默认直接输出
     * @return string
     */
    function output($return = false) {
        if ($return) {
            return $this->output;
        } else {
            echo $this->output;
        }
    }

    /**
     * 生成Limit语句
     *
     * @access public
     * @return string
     */
    function limit() {

        return (($this->curr-1)*$this->psize).','.$this->psize;
    }

} //End Class
?>
<?php
/**
 * 这个类是基于上面的类进行的修改， 用在生成静态HTML文件时用。
 * 一个用于Mysql数据库的分页类,
 *
 * @author      Avenger <avenger@php.net>
 * @version     1.0
 * @lastupdate  2003-04-08 11:11:33
 *
 *
 * 使用实例:
 * $p = new show_page;      //建立新对像
 * $p->file="ttt.php";      //设置文件名，默认为当前页
 * $p->pvar="pagecount";    //设置页面传递的参数，默认为p
 * $p->setvar(array("a" => '1', "b" => '2'));   //设置要传递的参数,要注意的是此函数必须要在 set 前使用，否则变量传不过去
 * $p->set(20,2000,1);      //设置相关参数，共三个，分别为'页面大小'、'总记录数'、'当前页(如果为空则自动读取GET变量)'
 * $p->output(0);           //输出,为0时直接输出,否则返回一个字符串
 * echo $p->limit();        //输出Limit子句。在sql语句中用法为 "SELECT * FROM TABLE LIMIT {$p->limit()}";
 *
 */


class show_page2 {

    /**
     * 页面输出结果
     *
     * @var string
     */
    var $output;

    /**
     * 使用该类的文件,默认为 index
     *
     * @var string
     */
    var $file;
    /**
     * 使用该类的文件的类型,默认为 .html
     *
     * @var string
     */
    var $fileext;

    /**
     * 页数传递变量，默认为 'p'
     *
     * @var string
     */
    var $pvar = "p";

    /**
     * 页面大小
     *
     * @var integer
     */
    var $psize;

    /**
     * 当前页面
     *
     * @var ingeger
     */
    var $curr;

    /**
     * 要传递的变量数组
     *
     * @var array
     */
    var $varstr;                //无用参数

    /**
     * 总页数
     *
     * @var integer
     */
    var $tpage;

    /**
     * 分页设置
     *
     * @access public
     * @param int $pagesize 页面大小
     * @param int $total    总记录数
     * @param int $current  当前页数，默认会自动读取
     * @return void
     */
    function set($pagesize=20,$total,$current=false) {
        global $HTTP_SERVER_VARS,$HTTP_GET_VARS;

        $this->tpage = ceil($total/$pagesize);
        if (!$current) {$current = $HTTP_GET_VARS[$this->pvar];}
        if ($current>$this->tpage) {$current = $this->tpage;}
        if ($current<1) {$current = 1;}

        $this->curr  = $current;
        $this->psize = $pagesize;
        $this->output= '';  //这条语句不可少，用来处理生成多页时，产生多页的分页信息累加 的错误

        //if (!$this->file) {$this->file = $HTTP_SERVER_VARS['PHP_SELF'];}
        
        if(!$this->file) {$this->file='index';}
        if(!$this->fileext) {$this->fileext='.html';}
        
        if ($this->tpage > 1) {
            //show total pages
            $this->output .= "第<font color=red>".$current."</font>页  共<b><font color=red>".$this->tpage."</font></b>页&nbsp;".$total."条信息&nbsp;&nbsp;".$this->psize."/页&nbsp;&nbsp;";
            
            if ($current>10) {//<a href=' index        _ 5              .html'           title="前十页">&lt;&lt;&lt;</a>&nbsp;';
                if($current-10 == 1){
                    $this->output.='<a href="'.$this->file.$this->fileext.'" title="前十页">[&lt;&lt;]</a>&nbsp;';
                } else {
                    $this->output.='<a href="'.$this->file.'_'.($current-10).$this->fileext.'" title="前十页">[&lt;&lt;]</a>&nbsp;';
                }
            }
            if ($current>1) {
                if($current-1 == 1){    //处理每一页 index.html
                    $this->output.='<a href='.$this->file.$this->fileext.' title="前一页">上页</a>&nbsp;';
                } else {
                    $this->output.='<a href='.$this->file.'_'.($current-1).$this->fileext.' title="前一页">上页</a>&nbsp;';
                }
            }

            $start  = floor($current/10)*10;
            $end    = $start+9;

            if ($start<1)           {$start=1;}
            if ($end>$this->tpage)  {$end=$this->tpage;}

            for ($i=$start; $i<=$end; $i++) {
                if ($current==$i) {
                    $this->output.='<font color="red">'.$i.'</font>&nbsp;';    //输出当前页数
                } else {    //第一页格式比较特殊为： index.html
                    if($i == 1) $this->output.='<a href="'.$this->file.$this->fileext.'">['.$i.']</a>&nbsp;';    //输出页数
                    else $this->output.='<a href="'.$this->file.'_'.$i.$this->fileext.'">['.$i.']</a>&nbsp;';    //输出页数
                }
            }

            if ($current<$this->tpage) {    //<a href="index _ 6 .html" title="下一页">&gt;&gt;</a>&nbsp;;
                $this->output.='<a href="'.$this->file.'_'.($current+1).$this->fileext.'" title="下一页">下页</a>&nbsp;';
            }
            //die($this->tpage);
            if ($this->tpage>10 && ($this->tpage-$current)>=10 ) { // <a href=" index _ 15 .html" title="下十页">&gt;&gt;&gt;</a>;
                $this->output.='<a href="'.$this->file.'_'.($current+10).$this->fileext.'" title="下十页">[&gt;&gt;]</a>';
            }
        }else{
            $this->output='第<font color=red>'.$current.'</font>页&nbsp;共<font color=red>'.$this->tpage.'</font>页&nbsp;<font color=red><b>'.$total.'</b></font>条信息&nbsp;'.$this->psize.'/页&nbsp;&nbsp;';
        }
        
    }

    /**
     * 要传递的变量设置
     *
     * @access public
     * @param array $data   要传递的变量，用数组来表示，参见上面的例子
     * @return void
     */ 
    function setvar($data) {
        foreach ($data as $k=>$v) {
            $this->varstr.='&amp;'.$k.'='.urlencode($v);
        }
    }

    /**
     * 分页结果输出
     *
     * @access public
     * @param bool $return 为真时返回一个字符串，否则直接输出，默认直接输出
     * @return string
     */
    function output($return = false) {
        if ($return) {
            return $this->output;
        } else {
            echo $this->output;
        }
    }

    /**
     * 生成Limit语句
     *
     * @access public
     * @return string
     */
    function limit() {
        return (($this->curr-1)*$this->psize).','.$this->psize;
    }

} //End Class
?>
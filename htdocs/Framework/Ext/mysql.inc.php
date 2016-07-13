<?php basename($_SERVER['PHP_SELF'])=='mysql.inc.php'&&header('Location:http://'.$_SERVER['HTTP_HOST']); //禁止直接访问本页
error_reporting(E_ALL & ~E_DEPRECATED);
/**
※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
【文件名】: mysql.inc.php
【作  用】: mysql数据库操作类
【作  者】: Riyan
【版  本】: version 2.0
【修改日期】: 2010/02/11
※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
**/

class mysql{
    private $host;         // 数据库主机
    private $user;         // 数据库用户名
    private $pass;         // 数据库密码
    private $data;         // 数据库名
    private $conn;         // 数据库连接标识
    private $sql;          // sql语句
    private $code;         // 数据库编码，GBK,UTF8,GB2312
    private $result;       // 执行query命令的结果数据集
    private $errLog=false;  // 是否开启错误日志,默认开启
    private $showErr=true; // 显示所有错误,具有安全隐患,默认开启

    private $pageNo=1;     // 当前页
    private $pageAll=1;    // 总页数
    private $rsAll=0;      // 总记录
    private $pageSize=10;  // 每页显示记录条数

    /******************************************************************
    -- 函数名：__construct($host,$user,$pass,$data,$code,$conn)
    -- 作  用：构造函数
    -- 参  数：$host 数据库主机地址(必填)
              $user 数据库用户名(必填)
              $pass 数据库密码(必填)
              $data 数据库名(必填)
              $conn 数据库连接标识(必填)
              $code 数据库编码(必填)
    -- 返回值：无 
    -- 实  例：无
    *******************************************************************/
    public function __construct($host,$user,$pass,$data,$code='utf8',$conn='conn'){
        $this->host=$host;
        $this->user=$user;
        $this->pass=$pass;
        $this->data=$data;
        $this->conn=$conn;
        $this->code=$code;
        $this->connect();
    }

    public function __get($name){return $this->$name;}

    public function __set($name,$value){$this->$name=$value;}

    // 数据库连接
    private function connect(){
        if($this->conn=='pconn') $this->conn=mysql_pconnect($this->host,$this->user,$this->pass); // 永久链接
        else $this->conn=mysql_connect($this->host,$this->user,$this->pass); // 临时链接
        if(!$this->conn) $this->show_error('无法连接服务器');
        $this->select_db($this->data);
        $this->query('SET NAMES '.$this->code);
        $this->query("SET CHARACTER_SET_CLIENT='{$this->code}'"); 
        $this->query("SET CHARACTER_SET_RESULTS='{$this->code}'");
    }

    // 数据库选择
    public function select_db($data){
        $result=mysql_select_db($data,$this->conn);
        if(!$result) $this->show_error('无法连接数据库'.$data);
        return $result;
    }

    /******************************************************************
    -- 函数名：get_info($num)
    -- 作  用：取得 MySQL 服务器信息
    -- 参  数：$num 信息值(选填)
    -- 返回值：字符串
    -- 实  例：无
    *******************************************************************/
    public function get_info($num){
        switch ($num){
            case 1:
                return mysql_get_server_info(); // 取得 MySQL 服务器信息
                break;
            case 2:
                return mysql_get_host_info();   // 取得 MySQL 主机信息
                break;
            case 3:
                return mysql_get_proto_info();  // 取得 MySQL 协议信息
                break;
            default:
                return mysql_get_client_info(); // 取得 MySQL 客户端信息
        }
    }

    /******************************************************************
    -- 函数名：query($sql)
    -- 作  用：数据库执行语句，可执行查询添加修改删除等任何sql语句
    -- 参  数：$sql sql语句(必填)
    -- 返回值：布尔
    -- 实  例：无
    *******************************************************************/
    public function query($sql){
        if(empty($sql)) $this->show_error('SQL语句为空');
        $this->sql=preg_replace('/ {2,}/',' ',trim($sql));
        $this->result=mysql_query($this->sql,$this->conn);
        if(!$this->result) $this->show_error('SQL语句有误',true);
        return $this->result;
    }

    /******************************************************************
    -- 函数名：create_db($data)
    -- 作  用：创建添加新的数据库
    -- 参  数：$data 数据库名称(必填)
    -- 返回值：字符串
    -- 实  例：无
    *******************************************************************/
    public function create_database($data=''){$this->query("CREATE DATABASE {$data}");}

    // 查询服务器所有数据库
    public function show_database(){
        $this->query('SHOW DATABASES');
        $db=array();
        while ($row=$this->fetch_array()) $db[]=$row['Database'];
        return $db;
    }

    // 查询数据库下所有的表
    public function show_tables($data=''){
        if(!empty($data)) $db=' FROM '.$data;
        $this->query('SHOW TABLES'.$data);
        $tables=array();
        while ($row=$this->fetch_row()) $tables[]=$row[0];
        return $tables;
    }

    /******************************************************************
    -- 函数名：copy_tables($tb1,$tb2,$where)
    -- 作  用：复制表
    -- 参  数：$tb1 新表名(必填)
              $tb2 待复制表的表名(必填)
              $Condition 复制条件(选填)
    -- 返回值：布尔
    -- 实  例：无
    *******************************************************************/
    public function copy_tables($tb1,$tb2,$Condition=''){$this->query("SELECT * INTO `{$tb1}` FROM `{$tb2}` {$Condition}");}

    /******************************************************************
    -- 函数名：Get($Table,$Fileds,$Condition,$Rows)
    -- 作  用：查询数据
    -- 参  数：$Table 表名(必填)
              $Fileds 字段名，默认为所有(选填)
              $Condition 查询条件(选填)
              $Rows 待查询记录条数，为0表示不限制(选填)
    -- 返回值：布尔
    -- 实  例：$DB->Get('mydb','user,password','order by id desc',10)
    *******************************************************************/
    public function Get($Table,$Fileds='*',$Condition='',$Rows=0){
        if(!$Fileds) $Fileds='*';
        if($Rows>0) $Condition.=" LIMIT 0,{$Rows}";
        $sql="SELECT {$Fileds} FROM `{$Table}` {$Condition}";
		
		
        return $this->query($sql);
    }

    // 只查询一条记录
    public function GetRs($Table,$Fileds='*',$Condition=''){
        if(!$Fileds) $Fileds='*';
        $this->query("SELECT {$Fileds} FROM `{$Table}` {$Condition} LIMIT 0,1");
        return $this->fetch_assoc();
    }

    /******************************************************************
    -- 函数名：Add($Table,$Data)
    -- 作  用：添加数据
    -- 参  数：$Table 表名(必填)
              $Data 待添加数据,可以为数组(必填)
    -- 返回值：布尔
    -- 实  例：$DB->Add('mydb',array('user'=>'admin','password'=>'123456','age'=>'18'))数组类型
              $DB->Add('mydb','user=admin,password=123456,age=18') 字符串类型
    *******************************************************************/
    public function Add($Table,$Data){
		$flag = false;
        if(!is_array($Data)){
            $arr=explode(',',$Data);
            $Data=array();
            foreach ($arr as $val){
                list($key,$val)=explode('=',$val);
                if(!$val) $val='';
                $Data[$key]=$val;
            }
        }
        $Fileds='`'.implode('`,`',array_keys($Data)).'`';
        $Value="'".implode("','",array_values($Data))."'";
        if(!$flag){
			$flag = $this->query("INSERT INTO `{$Table}` ({$Fileds}) VALUES ({$Value})");
		}
        return $flag;
    } 

    /******************************************************************
    -- 函数名：Set($Table,$Data,$Condition,$unQuot)
    -- 作  用：更改数据
    -- 参  数：$Table 表名(必填)
              $Data 待更改数据,可以为数组(必填)
              $Condition 更改条件(选填)
              $unQuot 不需要加引号的字段，用于字段的加减运算等情况，多个字段用,分隔或者写入一个数组(选填)
    -- 返回值：布尔
    -- 实  例：$DB->Set('mydb',array('user'=>'admin','password'=>'123456','WHERE id=1') 数组类型
              $DB->Set('mydb',"user='admin',password='123456'",'WHERE id=1') 字符串类型
    *******************************************************************/
    public function Set($Table,$Data,$Condition='',$unQuot=''){
		$flag = false;
        if(is_array($Data)){
            if(!is_array($unQuot)) $unQuot=explode(',',$unQuot);
            foreach ($Data as $key=>$val){
                $arr[]=$key.'='.(in_array($key,$unQuot)?$val:"'$val'");
            }
            $Value=implode(',',$arr);
        }else $Value=$Data;
     	if(!$flag){
			$flag = $this->query("UPDATE `{$Table}` SET {$Value} {$Condition}");
		}
        return $flag;
    }

    /******************************************************************
    -- 函数名：Del($Table,$Condition)
    -- 作  用：删除数据
    -- 参  数：$Table 表名(必填)
              $Condition 删除条件(选填)
    -- 返回值：布尔
    -- 实  例：$DB->Del('mydb','id=1')
    *******************************************************************/
	public function Del($Table,$Condition=''){
		$flag = false;
		if(!$flag){
			$flag = $this->query("DELETE FROM `{$Table}`".($Condition ? " WHERE {$Condition}":''));
		}
		return $flag;
	}
    // 取得结果数据
    public function result($result=''){
        if(empty($result)) $result=$this->result;
        if($result==null) $this->show_error('未获取到查询结果',true);
        return mysql_result($result);
    }

    /******************************************************************
    -- 函数名：fetch_array($Table,$Condition)
    -- 作  用：根据从结果集取得的行生成关联数组
    -- 参  数：$result 结果集(选填)
              $type 数组类型，可以接受以下值：MYSQL_ASSOC，MYSQL_NUM 和 MYSQL_BOTH(选填)
    -- 返回值：布尔
    -- 实  例：$DB->fetch_array('mydb','id=1')
    *******************************************************************/
    public function fetch_array($result='',$type=MYSQL_BOTH){
        if(empty($result)) $result=$this->result;
        if(!$result) $this->show_error('未获取到查询结果',true);
        return mysql_fetch_array($result,$type);
    }

    // 获取关联数组,使用$row['字段名']
    public function fetch_assoc($result=''){
        if(empty($result)) $result=$this->result;
        if(!$result) $this->show_error('未获取到查询结果',true);
        return mysql_fetch_assoc($result);
    }    

    // 获取数字索引数组,使用$row[0],$row[1],$row[2]
    public function fetch_row($result=''){
        if(empty($result)) $result=$this->result;
        if(!$result) $this->show_error('未获取到查询结果',true);
        return mysql_fetch_row($result);
    } 

    // 获取对象数组,使用$row->content 
    public function fetch_obj($result=''){
        if(empty($result)) $result=$this->result;
        if(!$result) $this->show_error('未获取到查询结果',true);
        return mysql_fetch_object($result);
    }  

    // 取得上一步 INSERT 操作产生的 ID
    public function insert_id(){return mysql_insert_id();}

    // 指向确定的一条数据记录
    public function data_seek($id){
        if($id>0) $id=$id-1;
        if(!mysql_data_seek($this->result,$id)) $this->show_error('指定的数据为空');
        return $this->result; 
    }

    /******************************************************************
    函数名：num_fields($result)
    作  用：查询字段数量
    参  数：$Table 数据库表名(必填)
    返回值：字符串
    实  例：$DB->num_fields("mydb")
    *******************************************************************/
    public function num_fields($result=''){
        if(empty($result)) $result=$this->result;
        if(!$result) $this->show_error('未获取到查询结果',true);
        return mysql_num_fields($result);
    }

    // 根据select查询结果计算结果集条数 
    public function num_rows($result=''){ 
        if(empty($result)) $result=$this->result;
        $rows=mysql_num_rows($result);
        if($result==null){
            $rows=0;
            $this->show_error('未获取到查询结果',true);
        }
        return $rows>0?$rows:0;
    }

    // 根据insert,update,delete执行结果取得影响行数 
    public function affected_rows(){return mysql_affected_rows();}

    // 获取地址栏参数
    public function getQuery($unset=''){ //$unset表示不需要获取的参数，多个参数请用,分隔(例如:getQuery('page,sort'))
        if(!empty($unset)){
            $arr=explode(',',$unset);
            foreach ($arr as $val) unset($_GET[$val]);
        }
		$list="";
        foreach ($_GET as $key=>$val) $list[]=$key.'='.urlencode($val);
        return is_array($list)?implode('&',$list):'';
    }

    /******************************************************************
    函数名：getPage($Table,$Fileds,$Condition,$pageSize)
    作  用：获取分页信息
    参  数：$Table 表名(必填)
           $Fileds 字段名，默认所有字段(选填)
           $Condition 查询条件(选填)
           $pageSize 每页显示记录条数，默认10条(选填)
    返回值：字符串
    实  例：无
    *******************************************************************/
    public function getPage($Table,$Fileds='*',$Condition='',$pageSize=10){
        if(intval($pageSize)>0){$this->pageSize=intval($pageSize);}
        if(isset($_GET['page']) && intval($_GET['page'])){$this->pageNo=intval($_GET['page']);}
        if(empty($Fileds)){$Fileds='*';}
        $sql="SELECT * FROM `{$Table}` {$Condition}";
        $this->query($sql);
        $this->rsAll=$this->num_rows();
        if($this->rsAll>0){
            $this->pageAll=ceil($this->rsAll/$this->pageSize);
            if($this->pageNo<1){$this->pageNo=1;}
            if($this->pageNo>$this->pageAll){$this->pageNo=$this->pageAll;}
            $sql="SELECT {$Fileds} FROM `{$Table}` {$Condition}".$this->limit(true);
            $this->query($sql);
        }
        return $this->rsAll;
    }
    
    /******************************************************************
    函数名：getPages($Table,$Fields,$Condition,$pageSize)
    作  用：多表查询获取分页信息
    参  数：$Table 表名(必填)
           $Fields 字段名，默认所有字段(选填)
           $Condition 查询条件(选填)
           $pageSize 每页显示记录条数，默认10条(选填)
    返回值：字符串
    实  例：无
    *******************************************************************/

     public function getPages($Table='', $Fields='*', $Condition='', $pageSize=10) {
        if (intval($pageSize) > 0) {
            $this->pageSize = intval($pageSize);
        }

        if (isset($_GET['page']) && intval($_GET['page'])) {
            $this->pageNo = intval($_GET['page']);
        }

        if (empty($Fields)) {
            $Fields='*';
        }
        $sql="SELECT * FROM {$Table} {$Condition}";
        
        $this->query($sql);
        $this->rsAll = $this->num_rows();

        if ($this->rsAll > 0) {
            $this->pageAll = ceil($this->rsAll / $this->pageSize);
            if ($this->pageNo < 1) {
                $this->pageNo=1;
            }
            if ($this->pageNo > $this->pageAll) {
                $this->pageNo = $this->pageAll;
            }
            $sql = "SELECT {$Fields} FROM {$Table} {$Condition}" . $this->limit(true);
            $this->query($sql);
        }

        return $this->rsAll;
    }
    
    // 构造分页limit语句，和getPage()函数搭配使用
    public function limit($str=false){
        $n=($this->pageNo-1)*$this->pageSize;
        return $str?' LIMIT '.$n.','.$this->pageSize:$n;
    }

    //显示分页，必须和getPage()函数搭配使用
    public function showPage($number=true){
        $pageBar='';
        if($this->pageAll>1){
            $pageBar.='<div class="page">'.chr(10);
            $url=$this->getQuery('page');
            $url=empty($url)?'?page=':'?'.$url.'&page=';
            if($this->pageNo>1){
                $pageBar.='<a class="pre" href="'.$url.'1">首页</a>'.chr(10);
                $pageBar.='<a class="pre" href="'.$url.($this->pageNo-1).'">上页</a>'.chr(10);
            }else{
                $pageBar.='<a class="nopre">首页</a>'.chr(10);
                $pageBar.='<a class="nopre">上页</a>'.chr(10);
            }
            if($number){
                $arr=array();
                if($this->pageAll<6){
                    for ($i=0;$i<$this->pageAll;$i++) $arr[]=$i+1;
                }else{
                    if($this->pageNo<3)
                        $arr=array(1,2,3,4,5);
                    elseif($this->pageNo<=$this->pageAll&&$this->pageNo>($this->pageAll-3))
                        for ($i=1;$i<6;$i++) $arr[]=$this->pageAll-5+$i;
                    else
                        for ($i=1;$i<6;$i++) $arr[]=$this->pageNo-3+$i;
                }
                foreach ($arr as $val){
                    if($val==$this->pageNo) $pageBar.='<a class="cur">'.$val.'</a>'.chr(10);
                    else $pageBar.='<a href="'.$url.$val.'">'.$val.'</a>'.chr(10);
                }
            }
            if($this->pageNo<$this->pageAll){
                $pageBar.='<a class="next" href="'.$url.($this->pageNo+1).'">下页</a>'.chr(10);
                $pageBar.='<a class="next" href="'.$url.$this->pageAll.'">尾页</a>'.chr(10);
            }else{
                $pageBar.='<a class="nonext">下页</a>'.chr(10);
                $pageBar.='<a class="nonext">尾页</a>'.chr(10);
            }
            $pageBar.='</div>'.chr(10);
        }
		echo '<style>
.page{width:auto;text-align:center;height:30px;margin-top:5px;}
.page a,
.page span{display:inline-block;}
.page a{width:26px;height:24px;line-height:24px;color:#36c;border:1px solid #ccc;}
.page a:hover,
.page a.cur{background:#ffede1;border-color:#fd6d01;color:#fd6d24;text-decoration:none;}
.page .pre,
.page .next,
.page .nopre,
.page .nonext{width:41px;height:24px;}
.page .pre,
.page .nopre{padding-left:16px;text-align:left;}
.page .next,
.page .nonext{padding-right:16px;text-align:right;}
.page .nopre,
.page .nonext{border:1px solid #ccc;color:#000;line-height:24px;}
.page .nopre{background:url(/Framework/Static/images/page/bg_pre_g.png) no-repeat 6px 8px;}
.page .pre,
.page .pre:hover{background:url(/Framework/Static/images/page/bg_pre.png) no-repeat 6px 8px;}
.page .nonext{background:url(/Framework/Static/images/page/bg_next_g.png) no-repeat 46px 8px;}
.page .next,
.page .next:hover{background:url(/Framework/Static/images/page/bg_next.png) no-repeat 46px 8px;}
</style>';
        echo $pageBar;
    }
	
	public function showPage2($number=true){
        $pageBar='';
        if($this->pageAll>1){
            $pageBar.='<div class="page">'.chr(10);
            $url=$this->getQuery('page');
            $url=empty($url)?'?page=':'?'.$url.'&page=';
            if($this->pageNo>1){
                $pageBar.='<a class="pre" href="'.$url.'1">首页</a>'.chr(10);
                $pageBar.='<a class="pre" href="'.$url.($this->pageNo-1).'">上页</a>'.chr(10);
            }else{
                $pageBar.='<span class="nopre">首页</span>'.chr(10);
                $pageBar.='<span class="nopre">上页</span>'.chr(10);
            }
            if($number){
                $arr=array();
                if($this->pageAll<6){
                    for ($i=0;$i<$this->pageAll;$i++) $arr[]=$i+1;
                }else{
                    if($this->pageNo<3)
                        $arr=array(1,2,3,4,5);
                    elseif($this->pageNo<=$this->pageAll&&$this->pageNo>($this->pageAll-3))
                        for ($i=1;$i<6;$i++) $arr[]=$this->pageAll-5+$i;
                    else
                        for ($i=1;$i<6;$i++) $arr[]=$this->pageNo-3+$i;
                }
                foreach ($arr as $val){
                    if($val==$this->pageNo) $pageBar.='<a class="cur">'.$val.'</a>'.chr(10);
                    else $pageBar.='<a href="'.$url.$val.'">'.$val.'</a>'.chr(10);
                }
            }
            if($this->pageNo<$this->pageAll){
                $pageBar.='<a class="next" href="'.$url.($this->pageNo+1).'">下页</a>'.chr(10);
                $pageBar.='<a class="next" href="'.$url.$this->pageAll.'">尾页</a>'.chr(10);
            }else{
                $pageBar.='<span class="nonext">下页</span>'.chr(10);
                $pageBar.='<span class="nonext">尾页</span>'.chr(10);
            }
            $pageBar.='<span>';
			//$pageBar.="页次:{$this->pageNo}/{$this->pageAll} {$this->pageSize}条/页 总记录:{$this->rsAll} 转到:"
            $pageBar.="转到第 <input class=\"pagetext\" id=\"page\" value=\"{$this->pageNo}\" type=\"text\" onblur=\"goPage('{$url}',{$this->pageAll});\" />";
            $pageBar.=" 页 <input class=\"pagebtn\" name=\"\" type=\"button\" value=\"确定\" /></span></div>".chr(10);
        }
        echo $pageBar;
    }
	//静态
	public function showWechatPage1($url="",$number=true){
        $pageBar='';
        if($this->pageAll>1){
            $pageBar.='<div id="turn_page">';
            if($this->pageNo>1){
                $pageBar.='<a href="'.$url.($this->pageNo-1).'" class="page_button"><<上一页</a>';
            }else{
                $pageBar.='<font class="page_noclick"><<上一页</font>';
            }
			$pageBar.='&nbsp;&nbsp;<span class="fc_red">'.$this->pageNo.'</span> / '.$this->pageAll.'&nbsp;&nbsp;';
            if($this->pageNo<$this->pageAll){
                $pageBar.='<a href="'.$url.($this->pageNo+1).'" class="page_button">下一页>></a>';
            }else{
                $pageBar.='<font class="page_noclick">下一页>></font>';
            }
            $pageBar.="</div>";
        }
        echo $pageBar;
    }
	
	public function showWechatPage($url="",$number=true){
        $pageBar='';
        if($this->pageAll>1){
            $pageBar.='<div id="turn_page">';
            if($this->pageNo>1){
                $pageBar.='<a href="'.$url.($this->pageNo-1).'/" class="page_button"><<上一页</a>';
            }else{
                $pageBar.='<font class="page_noclick"><<上一页</font>';
            }
			$pageBar.='&nbsp;&nbsp;<span class="fc_red">'.$this->pageNo.'</span> / '.$this->pageAll.'&nbsp;&nbsp;';
            if($this->pageNo<$this->pageAll){
                $pageBar.='<a href="'.$url.($this->pageNo+1).'/" class="page_button">下一页>></a>';
            }else{
                $pageBar.='<font class="page_noclick">下一页>></font>';
            }
            $pageBar.="</div>";
        }
        echo $pageBar;
    }
	//静态
	public function showStaticPage($url="",$number=true){
        $pageBar='';
        if($this->pageAll>1){
            $pageBar.='<div class="page">'.chr(10);
            if($this->pageNo>1){
                $pageBar.='<a href="'.$url.'1/">|<<</a>'.chr(10);
                $pageBar.='<a href="'.$url.($this->pageNo-1).'/">|<</a>'.chr(10);
            }else{
                $pageBar.='<a>|<<</a>'.chr(10);
                $pageBar.='<a>|<</a>'.chr(10);
            }
            if($number){
                $arr=array();
                if($this->pageAll<10){
                    for ($i=0;$i<$this->pageAll;$i++) $arr[]=$i+1;
                }else{
                    if($this->pageNo<5)
                        $arr=array(1,2,3,4,5,6,7,8,9);
                    elseif($this->pageNo<=$this->pageAll&&$this->pageNo>($this->pageAll-5))
                        for ($i=1;$i<10;$i++) $arr[]=$this->pageAll-9+$i;
                    else
                        for ($i=1;$i<10;$i++) $arr[]=$this->pageNo-5+$i;
                }
                foreach ($arr as $val){
                    if($val==$this->pageNo) $pageBar.='<a class="cur">'.$val.'</a>'.chr(10);
                    else $pageBar.='<a href="'.$url.$val.'/">'.$val.'</a>'.chr(10);
                }
            }
            if($this->pageNo<$this->pageAll){
                $pageBar.='<a href="'.$url.($this->pageNo+1).'/">>|</a>'.chr(10);
                $pageBar.='<a href="'.$url.$this->pageAll.'/">>>|</a>'.chr(10);
            }else{
                $pageBar.='<a>>|</a>'.chr(10);
                $pageBar.='<a>>>|</a>'.chr(10);
            }
            //$pageBar.='<span>';
			//$pageBar.="页次:{$this->pageNo}/{$this->pageAll} {$this->pageSize}条/页 总记录:{$this->rsAll} 转到:"
            //$pageBar.="转到第 <input class=\"pagetext\" id=\"page\" value=\"{$this->pageNo}\" type=\"text\" onblur=\"goPage('{$url}',{$this->pageAll});\" /> 页 <input class=\"pagebtn\" name=\"\" type=\"button\" value=\"确定\" /></span>";
            $pageBar.="</div>".chr(10);
        }
		
		echo '<style>
			.page{width:auto;text-align:center;height:30px;margin-top:5px;}
			.page a,
			.page span{display:inline-block;}
			.page a{width:26px;height:24px;line-height:24px;color:#36c;border:1px solid #ccc;}
			.page a:hover,
			.page a.cur{background:#ffede1;border-color:#fd6d01;color:#fd6d24;text-decoration:none;}
			.page .pre,
			.page .next,
			.page .nopre,
			.page .nonext{width:41px;height:24px;}
			.page .pre,
			.page .nopre{padding-left:16px;text-align:left;}
			.page .next,
			.page .nonext{padding-right:16px;text-align:right;}
			.page .nopre,
			.page .nonext{border:1px solid #ccc;color:#000;line-height:24px;}
			.page .nopre{background:url(/Framework/Static/images/page/bg_pre_g.png) no-repeat 6px 8px;}
			.page .pre,
			.page .pre:hover{background:url(/Framework/Static/images/page/bg_pre.png) no-repeat 6px 8px;}
			.page .nonext{background:url(/Framework/Static/images/page/bg_next_g.png) no-repeat 46px 8px;}
			.page .next,
			.page .next:hover{background:url(/Framework/Static/images/page/bg_next.png) no-repeat 46px 8px;}
			</style>';
        echo $pageBar;
    }
    // 获得客户端真实的IP地址
    public function getip(){
        if($_SERVER['REMOTE_ADDR']) return $_SERVER['REMOTE_ADDR'];
        elseif(getenv('REMOTE_ADDR')) return getenv('REMOTE_ADDR');
        else return '';
    }

    /******************************************************************
    -- 函数名：show_error($message,$sql)
    -- 作  用：输出显示错误信息
    -- 参  数：$msg 错误信息(必填)
              $sql 显示错误的SQL语句，在SQL语句错误时使用(选填)
    -- 返回值：字符串
    -- 实  例：无
    *******************************************************************/
    public function show_error($msg='',$sql=false){
        $err='['.mysql_errno().']'.mysql_error();
        if($sql) $sql='SQL语句：'.$this->sql;
        if($this->errLog){
            $dirs='error/'; //设置错误日志保存目录
            $fileName=date('Y-m-d').'.log';
            $filePath=$dirs.$fileName;
            if(!is_dir($dirs)){
                $dirs=explode('/',$dirs);
                $temp='';
                foreach($dirs as $dir){
                    $temp.=$dir.'/';
                    if(!is_dir($temp)){
                        mkdir($temp) or die('__无法建立目录'.$temp.'，自动取消记录错误信息');
                    }
                }
                $filePath=$temp.$fileName;
            }
            $text="错误事件：".$msg."\r\n错误原因：".$err."\r\n".($sql?$sql."\r\n":'')."客户端IP：".$this->getip()."\r\n记录时间：".date('Y-m-d H:i:s')."\r\n\r\n";
            $log='错误日志：__'.(error_log($text,3,$filePath)?'此错误信息已被自动记录到日志'.$fileName:'写入错误信息到日志失败');
        }
        if($this->showErr){
      echo '
      <fieldset class="errlog">
        <legend>错误信息提示</legend>
        <label class="tip">错误事件：'.$err.'</label><br>
        <label class="msg">错误原因：'.$msg.'</label><br>
        <label class="sql">'.$sql.'</label><br>
      </fieldset>';
      exit();
        }
    }

    /******************************************************************
    -- 函数名：drop($table)
    -- 作  用：删除表(请慎用,无法恢复)
    -- 参  数：$table 要删除的表名，默认为所有(选填)
    -- 返回值：无
    -- 实  例：$DB->drop('mydb')
    *******************************************************************/
    public function drop($table){
        if($table){
            $this->query("DROP TABLE IF EXISTS `{$table}`");
        }else{
            $rst=$this->query('SHOW TABLES'); 
            while ($row=$this->fetch_array()){
                $this->query("DROP TABLE IF EXISTS `{$row[0]}`");
            }
        }
    }

    /******************************************************************
    -- 函数名：makeSql($table)
    -- 作  用：从数据表读取信息并生成SQL语句
    -- 参  数：$table 待读取的表名(必填)
    -- 返回值：字符串
    -- 实  例：无
    *******************************************************************/
    public function makeSql($table){
        $result=$this->query("SHOW CREATE TABLE `{$table}`");
        $row=$this->fetch_row($result);
        $sqlStr='';
        if($row){
            $sqlStr.="-- ---------------------------------------------------------------\r\n";
            $sqlStr.="-- Table structure for `{$table}`\r\n";
            $sqlStr.="-- ---------------------------------------------------------------\r\n";
            $sqlStr.="DROP TABLE IF EXISTS `{$table}`;\r\n{$row[1]};\r\n";
            $this->Get($table);
            $fields=$this->num_fields();
            if($this->num_rows()>0){
                $sqlStr.="\r\n";
                $sqlStr.="-- ---------------------------------------------------------------\r\n";
                $sqlStr.="-- Records of `{$table}`\r\n";
                $sqlStr.="-- ---------------------------------------------------------------\r\n";
                while ($row=$this->fetch_row()){
                    $comma='';
                    $sqlStr.="INSERT INTO `{$table}` VALUES (";
                    for($i=0;$i<$fields;$i++){
                        $sqlStr.=$comma."'".mysql_escape_string($row[$i])."'";
                        $comma=',';
                    }
                    $sqlStr.=");\r\n";
                }
            }
            $sqlStr.="\r\n";
        }
        return $sqlStr;
    }

    /******************************************************************
    -- 函数名：readSql($filePath)
    -- 作  用：读取SQL文件并过滤注释
    -- 参  数：$filePath SQL文件路径(必填)
    -- 返回值：字符串/布尔/数组
    -- 实  例：无
    *******************************************************************/
    public function readSql($filePath){
        if(!file_exists($filePath)) return false;
        $sql=file_get_contents($filePath);
        if(empty($sql)) return '';
        $sql=preg_replace('/(\/\*(.*)\*\/)/s','',$sql); //过滤批量注释
        $sql=preg_replace('/(--.*)|[\f\n\r\t\v]*/','',$sql); //过滤单行注释与回车换行符
        $sql=preg_replace('/ {2,}/',' ',$sql); //将两个以上的连续空格替换为一个，可以省略这一步
        $arr=explode(';',$sql);
        $sql=array();
        foreach ($arr as $str){
            $str=trim($str);
            if(!empty($str)) $sql[]=$str;
        }
        return $sql;
    }

    /******************************************************************
    -- 函数名：saveSql($sqlPath,$table)
    -- 作  用：将当前数据库信息保存为SQL文件
    -- 参  数：$sqlPath SQL文件保存路径，如果为空则自动以当前日期为文件名并保存到当前目录(选填)
              $table 待保存的表名，为空着表示保存所有信息(选填)
    -- 返回值：字符串
    -- 实  例：$DB->saveSql('../mydb.sql');
    *******************************************************************/
    public function saveSql($sqlPath='',$table=''){
        if(empty($table)){
            $result=$this->query('SHOW TABLES');
            while ($arr=$this->fetch_row($result)){
                $str=$this->makeSql($arr[0]);
                if(!empty($str)) $sql.=$str;
            }
            $text="/***************************************************************\r\n";
            $text.="-- Database: $this->data\r\n";
            $text.="-- Date Created: ".date('Y-m-d H:i:s')."\r\n";
            $text.="***************************************************************/\r\n\r\n";
        }else{
            $text='';
            $sql=$this->makeSql($table);
        }
        if(empty($sql)) return false;
        $text.=$sql;
        $dir=dirname($sqlPath);
        $file=basename($sqlPath);
        if(empty($file)) $file=date('YmdHis').'.sql';
        $sqlPath=$dir.'/'.$file;
        if(!empty($dir)&&!is_dir($dir)){
            $path=explode('/',$dir);
            $temp='';
            foreach ($path as $dir){
                $temp.=$dir.'/';
                if(!is_dir($temp)){
                    if(!mkdir($temp)) return false;
                }
            }
            $sqlPath=$temp.$file;
        }
        $link=fopen($sqlPath,'w+');
        if(!is_writable($sqlPath)) return false;
        return fwrite($link,$text);
        fclose($link);
    }

    /******************************************************************
    -- 函数名：loadSql($filePath)
    -- 作  用：从SQL文件导入信息到数据库
    -- 参  数：$filePath SQL文件路径(必填)
    -- 返回值：字符串
    -- 实  例：无
    *******************************************************************/
    public function loadSql($filePath){
        $val=$this->readSql($filePath);
        if($val==false) $this->show_error($filePath.'不存在');
        elseif(empty($val)) $this->show_error($filePath.'中无有效数据');
        else{
            $errList='';
            foreach ($val as $sql){
                $result=mysql_query($sql);
                if(!$result) $errList.='执行语句'.$sql.'失败<br />';
            }
            return $errList;
        }
        return false;
    }

    // 释放结果集
    public function free(){
		if(is_resource($this->result)){
			mysql_free_result($this->result);
		}
	}

    // 关闭数据库
    public function close(){mysql_close($this->conn);}
	
	public function toArray($resource = ''){
		if(!empty($this->result)){
			$resource = $this->result;			
		}
	
		$result = array();
		
		while($item = $this->fetch_assoc($resource)){
			$result[] = $item;
		}
		
		return $result;
	}

    // 析构函数，自动关闭数据库,垃圾回收机制
    public function __destruct(){
        //$this->free();
        $this->close();
    }
}
?>
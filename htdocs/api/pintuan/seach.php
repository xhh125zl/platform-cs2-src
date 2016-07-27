<?php
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
	//头文件
	if(isset($_GET["id"])){
	  $ca=$_GET["id"];
	}else{

	}
	//对搜索页面的处理
	 if(!empty($_POST['name'])){
	 	$name=$_POST['name'];
	    //and stoptime>='.$time.'时间判断
	    $DB->get("pintuan_products","*",'where pintuan_type=0  and Products_Name like "%,'.$name.',%" and Users_ID="'.$UsersID.'"');
	    $sosuo=$DB->toArray();
	    if(empty($sosuo)) {
	    	echo" ";
	    }else{
	    	echo"";
	    }
 	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>搜索</title>
</head>
<link href="/static/api/pintuan/css/css.css" type="text/css" rel="stylesheet">
<link href="/static/api/pintuan/css/tab.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
<body>
<div class="w">
	<?php
			$DB->query("SELECT * FROM `pintuan_category` where Users_ID='".$UsersID."' and parent_id=0");
			$r=$DB->fetch_assoc();
	?>
<div>
	<form action="/api/<?php echo $UsersID;?>/pintuan/sousuo/<?php echo $r['cate_id'];?>/" method="post">
			<span class="box l"><input type="text" class="text" name="name" value="" style="width:99%;"/></span>
			<span class="btnSubmit l"><a id="seach">搜索</a></span>
			<input name="sousuo" type="submit" value="搜索"  style="display:none;" class="btnSubmit"/>
	</form>
</div>
<div class="clear"></div>
<!--代码开始 -->
<script type="text/javascript">

$('#seach').click(function() {
	$('input[name=sousuo]').click();
});

<!--
//切换到相关页
function gopage(n) 
{
  var tag = document.getElementById("menu").getElementsByTagName("li");
  var taglength = tag.length;

  for (i=1;i<=taglength;i++)
  {
    document.getElementById("m"+i).className="";
    document.getElementById("c"+i).style.display='none';
  }
    document.getElementById("m"+n).className="on";
    document.getElementById("c"+n).style.display='';
}
//-->
</script>
<div id="wrap">
          <div id="menu">
                <ul>
                <?php
            	 	//分类获取
					$DB->query("SELECT * FROM `pintuan_category` where Users_ID='".$UsersID."' and parent_id=0");
					$cate=$DB->toArray();
					//总数
					$num=count($cate);
					//对分类的更改
					for ($i=0; $i<$num; $i++) { 
						echo'
							<li class="cate" id="m'.$i.'"><a class="href" href="/api/'.$UsersID.'/pintuan/seach/'.$cate[$i]['cate_id'].'/" onmouseover="javascript:gopage('.$i.')">'.$cate[$i]['cate_name'].'</a>
							<input type="hidden" class="cate" value="'.$cate[$i]['cate_id'].'"></li>
							';
					}
                ?>	

                </ul>
          </div>
          <div id="content">
	               <div id="c1">
	            <!-- 获取第一个层-->
          		<?php
          			$ca=$_GET["id"];
          			if($ca=='0'){
          				$DB->query("SELECT * FROM `pintuan_category` where Users_ID='".$UsersID."'");
          				$rs=$DB->fetch_assoc();
          				$ca=$rs['cate_id'];
          			}
          			$DB->query("SELECT * FROM `pintuan_category` where Users_ID='".$UsersID."' and cate_id='".$ca."'");
          			$cate=$DB->toArray();
          			//获取当前分类
					$DB->query("SELECT * FROM `pintuan_products` where Products_Category like '%".$ca."%' and Users_ID='".$UsersID."'");
					$prodects=$DB->toArray();
					foreach ($cate as $k => $v) {
						echo '<ul>';
						foreach ($prodects as $k => $val){	
							echo '<li class="l"><a href="/api/'.$UsersID.'/pintuan/xiangqing/'.$val['Products_ID'].'/">
							<img style="height:80px;" src="'.json_decode($val['Products_JSON'],true)['ImgPath']['0'].'"><br/>'.sub_str($val['Products_Name'],10,false).'</a></li>';	
						}
						echo '</ul>';
					}	
          		?> 
	              </div>
                  <!-- 2层 -->
                  <div id="c2" style="display:none">
                        <!-- 获取第2个层 -->
		          		<?php
		          			$ca=$_GET["id"];
		          			$DB->query("SELECT * FROM `pintuan_category` where Users_ID='".$UsersID."' and cate_id='".$ca."'");
		          			$cate=$DB->toArray();
		          			//获取当前分类
							$DB->query("SELECT * FROM `pintuan_products` where Products_Category like '%".$ca."%' and Users_ID='".$UsersID."'");
							$prodects=$DB->toArray();
							foreach ($cate as $k => $v) {
								echo '<h1>'.$v['cate_name'].'</h1>';
								echo '<ul>';
								foreach ($prodects as $k => $val){	
								echo '<li class="l"><a href="/api/'.$UsersID.'/pintuan/xiangqing/'.$val['Products_ID'].'/"><img style="height:80px;" src="'.json_decode($val['Products_JSON'],true)['ImgPath']['0'].'"><br/>'.sub_str($val['Products_Name'],10,false).'</a></li>';	
						}
								echo '</ul>';
							}	
		          		?> 
                  </div>
                  <!-- 3层 -->
                  <div id="c3" style="display:none">
                         <!-- 获取第3个层 -->
		          		<?php
		          			$ca=$_GET["id"];
		          			$DB->query("SELECT * FROM `pintuan_category` where Users_ID='".$UsersID."' and cate_id='".$ca."'");
		          			$cate=$DB->toArray();
		          			//获取当前分类
							$DB->query("SELECT * FROM `pintuan_products` where Products_Category like '%".$ca."%' and Users_ID='".$UsersID."'");
							$prodects=$DB->toArray();
							foreach ($cate as $k => $v) {
								echo '<h1>'.$v['cate_name'].'</h1>';
								echo '<ul>';
								foreach ($prodects as $k => $val){	
									echo '<li class="l"><a href="/api/'.$UsersID.'/pintuan/xiangqing/'.$val['Products_ID'].'/"><img style="height:80px;" src="'.json_decode($val['Products_JSON'],true)['ImgPath']['0'].'"><br/>'.sub_str($val['Products_Name'],10,false).'</a></li>';	
								}
								echo '</ul>';
							}	
		          		?> 
                  </div>
          </div>
</div>
<div class="clear"></div>
<!--代码结束 -->
<div class="kb"></div>
<div class="clear"></div>
<div style="height:70px;"></div>
<div class="cotrs">
	<a  href="/api/<?php echo $UsersID;?>/pintuan/"><img src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br />首页</a>
	<a  class="thisclass" href="<?php echo "/api/$UsersID/pintuan/seach/0/"; ?>"><img src="/static/api/pintuan/images/002-2.png" width="22px" height="22px" style="margin-top:3px;"/><br />搜索</a>
	<a href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"><img src="/static/api/pintuan/images/002-3.png" width="22px" height="22px" style="margin-top:3px;"/><br />我的</a>
</div>

</div>
</body>

</html>

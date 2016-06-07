<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$rsConfig = $DB->GetRs("hongbao_config","*","where usersid='".$_SESSION["Users_ID"]."'");
if(!$rsConfig){
	header("location:config.php");
}
$condition = "where usersid='".$_SESSION["Users_ID"]."'";
$OrderBy = "actid desc";
if(isset($_GET['search'])){
	if($_GET['Status']!=""){
		$condition .= " and status=".intval($_GET['Status']);
	}
	if($_GET['Tags']!=""){
		if(intval($_GET['Tags'])==0){
			$condition .= " and money==0";
		}else{
			$condition .= " and money>0";
		}		
	}
	if($_GET['OrderBy']){
		$OrderBy = $_GET['OrderBy'];
	}
}
$condition .= " order by ".$OrderBy;
$Status = array('<font style="color:blue">未拆启</font>','<font style="color:red">已拆启</font>');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
#users .search{padding:10px; background:#f7f7f7; border:1px solid #ddd; margin-bottom:8px; font-size:12px;}
#users .search *{font-size:12px;}
#users .search .search_btn{background:#1584D5; color:white; border:none; height:22px; line-height:22px; width:50px;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
		<li class=""><a href="rules.php">活动规则</a></li>
        <li class=""><a href="prize.php">红包管理</a></li>
		<li class="cur"><a href="users.php">用户列表</a></li>
      </ul>
    </div>
    <div id="users" class="r_con_wrap">
      <form class="search" method="get" action="?">
         状态：
        <select name="Status">
          <option value="">全部</option>
          <option value="1">已拆启</option>
          <option value="0">未拆启</option>
        </select>
         &nbsp;抢中状态：
        <select name="Tags">
          <option value="">全部</option>
          <option value="1">抢中红包</option>
          <option value="0">未抢中红包</option>
        </select>
        &nbsp;排序：
        <select name="OrderBy">
          <option value="actid desc">默认</option>
          <option value="addtime DESC">发生时间降序</option>
          <option value="addtime ASC">发生时间升序</option>
          <option value="money DESC">红包金额降序</option>
          <option value="money ASC">红包金额升序</option>
          <option value="expire DESC">好友帮助指数降序</option>
          <option value="expire ASC">好友帮助指数升序</option>
        </select>
        <input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="18%" nowrap="nowrap">头像</td>
            <td width="15%" nowrap="nowrap">昵称</td>
            <td width="10%" nowrap="nowrap">红包金额</td>
			<td width="10%" nowrap="nowrap">好友帮助</td>            
            <td width="11%" nowrap="nowrap">状态</td>
            <td width="20%" nowrap="nowrap">产生时间</td>
            <td width="8%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php
			  $lists = array();
			  $DB->getPage("hongbao_act","*",$condition,10);			  
			  while($r=$DB->fetch_assoc()){
				  $lists[] = $r;
			  }
			  foreach($lists as $k=>$v){
				$user = $DB->GetRs("user","User_HeadImg,User_NickName","where User_ID=".$v["userid"]);
				$v["User_HeadImg"] = $user["User_HeadImg"];
				$v["User_NickName"] = $user["User_NickName"];
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $k+1;?></td>
            <td nowrap="nowrap"><img src="<?php echo $v["User_HeadImg"] ? $v["User_HeadImg"] : '/static/api/zhuli/images/user.jpg';?>" width="50" height="50" /></td>
            <td nowrap="nowrap"><?php echo $v["User_NickName"] ? $v["User_NickName"] : '微友助力';?></td>
            <td nowrap="nowrap"><?php echo $v["money"];?></td>
            <td nowrap="nowrap"><?php echo $v["expire"];?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$v["addtime"]) ?></td>
            <td nowrap="nowrap"><?php echo $Status[$v["status"]];?></td>
            <td class="last" nowrap="nowrap"><a href="friend.php?actid=<?php echo $v["actid"] ?>"><img src="/static/member/images/ico/view.gif" align="absmiddle" alt="查看好友" /></a></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>
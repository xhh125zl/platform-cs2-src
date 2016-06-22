<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Del("biz","Users_ID='".$_SESSION["Users_ID"]."' and Biz_ID=".$_GET["BizID"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
$OrderBy = "Biz_Status ASC,Biz_CreateTime DESC";
if(isset($_GET['search'])){
	if($_GET['Keyword']){
		$condition .= " and ".$_GET['Fields']." like '%".$_GET['Keyword']."%'";
	}
	if($_GET['GroupID']!=0){
		$condition .= " and Group_ID=".intval($_GET['GroupID']);
	}
	if($_GET['Status']!=""){
		$condition .= " and Biz_Status=".intval($_GET['Status']);
	}
	if($_GET['OrderBy']){
		$OrderBy = $_GET['OrderBy'];
	}
}

$condition .= " order by ".$OrderBy;

$_Status = array('<font style="color:#ff6600">正常</font>','<font style="color:blue">禁用</font>');
//商家分组
$groups = array();
$DB->Get("biz_group","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Group_index asc, Group_ID asc");
while($r=$DB->Fetch_assoc()){
	$groups[$r["Group_ID"]] = $r;
}


$salesman_array = array();
$is_salesman_array = array();
$DB->Get("distribute_account","Real_Name,Invitation_Code,Is_Salesman","where Users_ID='".$_SESSION["Users_ID"]."' and Invitation_Code <> ''");
while($row = $DB->fetch_assoc()){
    if (!empty($row['Invitation_Code'])){
        $salesman_array[$row['Invitation_Code']] = $row['Real_Name'];
	$is_salesman_array[$row['Invitation_Code']] = $row['Is_Salesman'];
    }
}

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
<style type="text/css">
#bizs .search{padding:10px; background:#f7f7f7; border:1px solid #ddd; margin-bottom:8px; font-size:12px;}
#bizs .search *{font-size:12px;}
#bizs .search .search_btn{background:#1584D5; color:white; border:none; height:22px; line-height:22px; width:50px;}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="index.php">商家列表</a></li>
        <li><a href="group.php">商家分组</a></li>
		<li><a href="apply.php">入驻申请列表</a></li>
		<li><a href="apply_config.php">入驻设置</a></li>
      </ul>
    </div>
	
    <div id="bizs" class="r_con_wrap">
      <div class="control_btn"><a href="add.php" class="btn_green btn_w_120">添加商家</a></div>
      <form class="search" method="get" action="?">
        <select name="Fields">
          <option value="Biz_Name">名称</option>
          <option value="Biz_Account">登录名</option>
          <option value="Biz_Address">地址</option>
          <option value="Biz_Contact">联系人</option>
          <option value="Biz_Phone">联系电话</option>
        </select>
        <input type="text" name="Keyword" value="" class="form_input" size="15" />
        
        所属分组：
        <select name="GroupID">
          <option value="">全部</option>
          <?php foreach($groups as $GroupID=>$g){?>
          <option value="<?php $GroupID;?>"><?php echo $g["Group_Name"];?></option>
          <?php }?>
        </select>
       
        状态：
        <select name="Status">
          <option value="">全部</option>
          <option value="0">正常</option>
          <option value="1">禁用</option>
        </select>
        排序：
        <select name="OrderBy">
          <option value="Biz_Status ASC,Biz_CreateTime DESC">默认</option>
          <option value="Biz_CreateTime DESC">添加时间降序</option>
          <option value="Biz_CreateTime ASC">添加时间升序</option>
        </select>
        <input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">ID</td>
            <td width="10%" nowrap="nowrap">登录账号</td>

             <td width="5%" nowrap="nowrap">邀请码</td>
            <td width="7%" nowrap="nowrap">业务员</td>
            <td width="15%" nowrap="nowrap">商家名称</td>
            <td width="10%" nowrap="nowrap">所属分组</td>
            <td width="7%" nowrap="nowrap">联系人</td>
            <td width="8%" nowrap="nowrap">联系电话</td>

            <td width="12%" nowrap="nowrap">添加时间</td>
            <td width="10%" nowrap="nowrap">状态</td>
            <td width="8%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php 
		  $lists = array();
		  $DB->getPage("biz","*",$condition,10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$rsBiz){
			  ?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $rsBiz["Biz_ID"] ?></td>
            <td><?php echo $rsBiz["Biz_Account"] ?></td>
            <td><?php echo $rsBiz['Invitation_Code']; ?></td>
             <td><?php 	if(!empty($rsBiz["Invitation_Code"]) && isset($salesman_array[$rsBiz["Invitation_Code"]])){
							if($is_salesman_array[$rsBiz["Invitation_Code"]]!= 1){
								echo '业务员被删除';
							}else{
								echo strlen($salesman_array[$rsBiz["Invitation_Code"]])>0?$salesman_array[$rsBiz["Invitation_Code"]]:'无昵称';
							} 
			}?></td>
            <td><?php echo $rsBiz["Biz_Name"] ?></td>
            <td><?php echo empty($groups[$rsBiz["Group_ID"]]["Group_Name"]) ? "" : $groups[$rsBiz["Group_ID"]]["Group_Name"]; ?></td>
            <td nowrap="nowrap"><?php echo $rsBiz['Biz_Contact']; ?></td>
            <td nowrap="nowrap"><?php echo $rsBiz["Biz_Phone"] ? $rsBiz["Biz_Phone"] : "暂无";?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d",$rsBiz["Biz_CreateTime"]) ?></td>
            <td nowrap="nowrap"><?php echo $_Status[$rsBiz["Biz_Status"]]; ?></td>
            <td class="last" nowrap="nowrap"><a href="edit.php?BizID=<?php echo $rsBiz["Biz_ID"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> <a href="?action=del&BizID=<?php echo $rsBiz["Biz_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
      <div style="background:#F7F7F7; border:1px #dddddd solid; height:40px; line-height:40px; font-size:12px; margin:10px 0px; padding-left:15px; color:#ff0000">提示：商家登陆地址 <a href="/biz/login.php" target="_blank">http://<?php echo $_SERVER['HTTP_HOST'];?>/biz/login.php</a></div>
    </div>
  </div>
</div>
</body>
</html>
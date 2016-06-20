<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
//删除级别
if(empty($_REQUEST['level'])){
	echo '缺少必要的参数';
	exit;
}

if(!isset($_REQUEST['type'])){
	echo '缺少必要的参数';
	exit;
}

$level = $_REQUEST['level'];
$type = $_REQUEST['type'];

if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		if(empty($_GET['LevelID'])){
			echo '<script language="javascript">alert("请选择要删除的级别");history.back();</script>';
			exit;
		}
		$LevelID = empty($_GET['LevelID'])?0:$_GET['LevelID'];
		$r = $DB->GetRs("distribute_account","Account_ID","where Level_ID=".$LevelID);
		if($r){
			echo '<script language="javascript">alert("该分销级别下有分销商，不能删除");history.back();</script>';
			exit;
		}
		$DB->Del("distribute_level","Level_ID=".$LevelID." and Users_ID='".$_SESSION["Users_ID"]."'");
		//更新分销商级别文件
		update_dis_level($DB,$_SESSION['Users_ID']);
		echo '<script language="javascript">window.location.href="level.php?level='.$level.'&type='.$type.'";</script>';
		exit;
	}
}

$complete = array();
$not_complete = 0;

//查询级别
$condition = "where Users_ID='".$_SESSION["Users_ID"]."' order by Level_ID asc";
$lists = array();
$DB->Get("distribute_level","*",$condition);
$num = 0;
while($r=$DB->fetch_assoc()){
	$r['PeopleLimit'] = json_decode($r['Level_PeopleLimit'],true);
	if($num==0){//第一个级别可以无门槛
		if($level == count($r['PeopleLimit']) && ($r['Level_LimitType']==$type || $r['Level_LimitType']==3)){
			$complete[] = $r['Level_ID'];
		}
	}else{
		if($level == count($r['PeopleLimit']) && $r['Level_LimitType']==$type){//查询已经更新过的分销商级别ID
			$complete[] = $r['Level_ID'];
		}
	}
	$lists[$r["Level_ID"]] = $r;
	$num++;
}

//判断关闭按钮显示
if(empty($lists)){
	$not_complete = 1;
}elseif(count($lists)==count($complete)){
	$not_complete = 0;
}else{
	$not_complete = 1;
}
$arr = array('一','二','三','四','五','六','七','八','九','十');
$_TYPE = array('直接购买','消费额','购买商品','无门槛');

if($type==2){
	$products = array();
	$DB->Get('shop_products','Products_ID,Products_Name','where Users_ID="'.$_SESSION['Users_ID'].'"');
	while($r = $DB->fetch_assoc()){
		$products[$r['Products_ID']] = $r['Products_Name'];
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
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/distribute/dis_level.js'></script>
    <script language="javascript">$(document).ready(dis_level.level_init);</script>
    <div id="orders" class="r_con_wrap">
      <input type="hidden" name="count" value="<?php echo count($lists);?>" />
      <input type="hidden" id="dis_type" value="<?php echo $type;?>" />
      <div class="control_btn">
      <a href="level_add.php?level=<?php echo $level;?>&type=<?php echo $type;?>" class="btn_green btn_w_120">添加级别</a>
      <?php if(!$not_complete){?>
      &nbsp;&nbsp;<a href="javascript:void(0);" class="btn_green btn_w_120" id="close_layer">关闭窗口</a>
      <?php }?>
      </div>
      <?php if($type==0){//直接购买?>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="6%" nowrap="nowrap">序号</td>
            <td width="16%" nowrap="nowrap">级别名称</td>
            <td width="10%" nowrap="nowrap">门槛</td>
            <td width="16%" nowrap="nowrap">价格</td>
            <td width="16%" nowrap="nowrap">人数限制</td>
            <td width="16%" nowrap="nowrap">佣金明细</td>
            <td width="12%" nowrap="nowrap">更新状态</td>
            <td width="8%" nowrap="nowrap">操作</td>
          </tr>
        </thead>
        <tbody>
         <?php
		 $i=0;
          foreach($lists as $key=>$value){
			  $i++;
			  $PeopleLimit = json_decode($value['Level_PeopleLimit'],true);
			  if($i==1 && $value['Level_LimitType']==3){//第一个无门槛
			  	  $Distributes = array();
			  	  $value["Level_LimitValue"] = '';
			  }elseif($value['Level_LimitType']!=0){
				  $Distributes = array();
				  $value["Level_LimitValue"] = '';
			  }else{
				  $Distributes = json_decode($value['Level_Distributes'],true);
			  }			  
		 ?>
         <tr>
            <td nowrap="nowrap"><?php echo $i;?></td>
            <td nowrap="nowrap"><?php echo $value["Level_Name"];?></td>
            <td nowrap="nowrap"><?php echo $i==1 ? $_TYPE[$value["Level_LimitType"]] : $_TYPE[$type];?></td>
            <td nowrap="nowrap"><font style="color:#F60"><?php echo $value["Level_LimitValue"];?></font></td>
            <td nowrap="nowrap">
            	<?php
                foreach($PeopleLimit as $k=>$v){
					if($k>1){
						echo '<br />';
					}
					echo $arr[$k-1].'级&nbsp;&nbsp;';
					if($v==0){
						echo '无限制';
					}elseif($v==-1){
						echo '禁止';
					}else{
						echo $v.'&nbsp;个';
					}
				}
				?>
            </td>
            <td nowrap="nowrap">
            	<?php
                foreach($Distributes as $k=>$v){
					if($k>1){
						echo '<br />';
					}
					echo $arr[$k-1].'级&nbsp;&nbsp;'.$v.'&nbsp;元';
				}
				?>
            </td>
            <td nowrap="nowrap"><?php echo in_array($key,$complete) ? '<font style="color:blue">已更新</font>' : '<font style="color:red">未更新</font>';?></td>
            <td nowrap="nowrap">
            	<a href="level_edit.php?LevelID=<?php echo $key;?>&level=<?php echo $level;?>&type=<?php echo $type;?>">[修改]</a>
                <?php if($i>1){?>
                <a href="?action=del&LevelID=<?php echo $key;?>&level=<?php echo $level;?>&type=<?php echo $type;?>">[删除]</a>
                <?php }?>
            </td>
          </tr>
         <?php }?>
        </tbody>
      </table>
      <?php }elseif($type==1){//消费额?>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="16%" nowrap="nowrap">级别名称</td>
            <td width="16%" nowrap="nowrap">门槛</td>
            <td width="19%" nowrap="nowrap">消费额</td>
            <td width="16%" nowrap="nowrap">人数限制</td>
            <td width="15%" nowrap="nowrap">更新状态</td>
            <td width="10%" nowrap="nowrap">操作</td>
          </tr>
        </thead>
        <tbody>
         <?php
		 $i=0;
          foreach($lists as $key=>$value){			  
			  $i++;
			  $PeopleLimit = json_decode($value['Level_PeopleLimit'],true);
			  if($i==1 && $value['Level_LimitType']==3){//第一个无门槛
				  $limit = '';
			  }elseif($value['Level_LimitType']!=1){
				  $limit = '';
			  }else{
				  $limit_arr = explode('|',$value['Level_LimitValue']);
				  $limit = $limit_arr[0]==0 ? '商城总消费'.$limit_arr[1].'元' : '一次性消费'.$limit_arr[1].'元'; 
			  }
		 ?>
         <tr>
            <td nowrap="nowrap"><?php echo $i;?></td>
            <td nowrap="nowrap"><?php echo $value["Level_Name"];?></td>
            <td nowrap="nowrap"><?php echo $i==1 ? $_TYPE[$value["Level_LimitType"]] : $_TYPE[$type];?></td>
            <td nowrap="nowrap"><font style="color:#F60"><?php echo $limit;?></font></td>
            <td nowrap="nowrap">
            	<?php
                foreach($PeopleLimit as $k=>$v){
					if($k>1){
						echo '<br />';
					}
					echo $arr[$k-1].'级&nbsp;&nbsp;';
					if($v==0){
						echo '无限制';
					}elseif($v==-1){
						echo '禁止';
					}else{
						echo $v.'&nbsp;个';
					}
				}
				?>
            </td>
            <td nowrap="nowrap"><?php echo in_array($key,$complete) ? '<font style="color:blue">已更新</font>' : '<font style="color:red">未更新</font>';?></td>
            <td nowrap="nowrap">
            	<a href="level_edit.php?LevelID=<?php echo $key;?>&level=<?php echo $level;?>&type=<?php echo $type;?>">[修改]</a>
                <?php if($i>1){?>
                <a href="?action=del&LevelID=<?php echo $key;?>&level=<?php echo $level;?>&type=<?php echo $type;?>">[删除]</a>
                <?php }?>
            </td>
          </tr>
         <?php }?>
        </tbody>
      </table>
      <?php }elseif($type==2){//购买商品?>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="16%" nowrap="nowrap">级别名称</td>
            <td width="12%" nowrap="nowrap">门槛</td>
            <td width="24%" nowrap="nowrap">商品</td>
            <td width="16%" nowrap="nowrap">人数限制</td>
            <td width="12%" nowrap="nowrap">更新状态</td>
            <td width="10%" nowrap="nowrap">操作</td>
          </tr>
        </thead>
        <tbody>
         <?php
		 $i=0;
          foreach($lists as $key=>$value){			  
			  $i++;
			  $PeopleLimit = json_decode($value['Level_PeopleLimit'],true);
			  if($i==1 && $value['Level_LimitType']==3){//第一个无门槛
				  $limit = '';
			  }elseif($value['Level_LimitType']!=2){
				  $limit = '';
			  }else{
				  $limit_arr = explode('|',$value['Level_LimitValue']);
				  if($limit_arr[0]==0){//任意商品
				  	  $limit = '购买任意商品';
				  }else{
					  $limit = '购买以下商品：';
					  $pids = explode(',',$limit_arr[1]);
					  foreach($pids as $id){
						  $limit .= empty($products[$id]) ? '' : '<br />'.$products[$id];
					  }
				  } 
			  }
		 ?>
         <tr>
            <td nowrap="nowrap"><?php echo $i;?></td>
            <td nowrap="nowrap"><?php echo $value["Level_Name"];?></td>
            <td nowrap="nowrap"><?php echo $i==1 ? $_TYPE[$value["Level_LimitType"]] : $_TYPE[$type];?></td>
            <td nowrap="nowrap"><font style="color:#F60"><?php echo $limit;?></font></td>
            <td nowrap="nowrap">
            	<?php
                foreach($PeopleLimit as $k=>$v){
					if($k>1){
						echo '<br />';
					}
					echo $arr[$k-1].'级&nbsp;&nbsp;';
					if($v==0){
						echo '无限制';
					}elseif($v==-1){
						echo '禁止';
					}else{
						echo $v.'&nbsp;个';
					}
				}
				?>
            </td>
            <td nowrap="nowrap"><?php echo in_array($key,$complete) ? '<font style="color:blue">已更新</font>' : '<font style="color:red">未更新</font>';?></td>
            <td nowrap="nowrap">
            	<a href="level_edit.php?LevelID=<?php echo $key;?>&level=<?php echo $level;?>&type=<?php echo $type;?>">[修改]</a>
                <?php if($i>1){?>
                <a href="?action=del&LevelID=<?php echo $key;?>&level=<?php echo $level;?>&type=<?php echo $type;?>">[删除]</a>
                <?php }?>
            </td>
          </tr>
         <?php }?>
        </tbody>
      </table>
      <?php }?>
    </div>
  </div>
</div>
</body>
</html>
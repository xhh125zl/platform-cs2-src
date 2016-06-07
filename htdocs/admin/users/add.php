<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$DB->showErr=false;
require_once('right.php');
if($_POST){
	$flag=true;
	$msg="";
	mysql_query("begin");
	if(empty($_POST["Users_Account"])){
		echo '<script language="javascript">alert("登录帐号不能为空！");window.location="javascript:history.back()";</script>';
		exit();
	}
	if(empty($_POST["Users_PasswordA"]) || empty($_POST["Users_PasswordB"])){
		echo '<script language="javascript">alert("登录密码和确认密码都必须填写！");window.location="javascript:history.back()";</script>';
		exit();
	}
	if($_POST["Users_PasswordA"]!=$_POST["Users_PasswordB"]){
		echo '<script language="javascript">alert("登录密码和确认密码不一致，请修改！");window.location="javascript:history.back()";</script>';
		exit();
	}
	$rsUsers=$DB->GetRs("users","*","where Users_Account='".$_POST["Users_Account"]."'");
	if($rsUsers){
		echo '<script language="javascript">alert("该用户已经存在，请修改！");window.location="javascript:history.back()";</script>';
		exit();
	}else{
		$rsUsers=$DB->GetRs("users","*","order by Users_ID desc");
		function RandChar($length=10){
			$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$temchars = '';
			for($i=0;$i<$length;$i++)
			{
				$temchars .= $chars[ mt_rand(0, strlen($chars) - 1) ];
			}
			return $temchars;
		}
		for($i=0;$i<=1;$i++){
			$Users_ID=RandChar(10);
			$rsUsers=$DB->GetRs("users","*","where Users_ID='".$Users_ID."'");
			$i=$rsUsers?0:1;
		}
		
		$Data=array(
			"Users_ID"=>$Users_ID,
			"Users_WechatToken"=>RandChar(10),
			"Users_Account"=>$_POST["Users_Account"],
			"Users_Password"=>md5($_POST["Users_PasswordA"]),
			"Users_Status"=>$_POST["Users_Status"],
			"Users_Right"=>json_encode((isset($_POST["JSON"])?$_POST["JSON"]:array()),JSON_UNESCAPED_UNICODE),
			"Users_ExpireDate"=>strtotime($_POST["Users_ExpireDate"]),
			"Users_Industry"=>$_POST["Users_Industry"],
			"Users_Remarks"=>$_POST["Users_Notes"],
			"Users_CreateTime"=>time()
		);
		$Add=$DB->Add("users",$Data);
		$flag=$flag&&$Add;
		//设置上传文件夹
		$save_path = $_SERVER["DOCUMENT_ROOT"].'/uploadfiles/'.$Users_ID.'/';
		if(!is_dir($save_path)){
			mkdir($save_path,0777,true);
		}
		if(!is_dir($save_path.'image/')){
			mkdir($save_path.'image/');
		}
		if(!is_dir($save_path.'media/')){
			mkdir($save_path.'media/');
		}
		if(!is_dir($save_path.'file/')){
			mkdir($save_path.'file/');
		}
		//设置首次关注
		$Data=array(
			"Users_ID"=>$Users_ID,
			"Reply_TextContents"=>"非常高兴认识你，新朋友！"
		);
		$Add=$DB->Add("wechat_attention_reply",$Data);
		$flag=$flag&&$Add;
		//初始化微商城
		$Data=array(
			"Users_ID"=>$Users_ID,
			"ShopName"=>$_POST["Users_Account"]."的微商城",
			"Skin_ID"=>9
		);
		$Add=$DB->Add("shop_config",$Data);
		$flag=$flag&&$Add;
		$skin_home = $DB->GetRs("shop_skin","Skin_Json","where Skin_ID=9");
		//初始化微商城首页
		$Data=array(
			"Users_ID"=>$Users_ID,
			"Skin_ID"=>9,
			"Home_Json"=>$skin_home["Skin_Json"]
		);
		$Add=$DB->Add("shop_home",$Data);
		$flag=$flag&&$Add;
		
		//初始化分销配置 edit 2016.3.23
		$Data = array(
			'Users_ID'=>$Users_ID
		);
		$Add=$DB->Add('distribute_config',$Data);
		$flag=$flag&&$Add;

		//初始化分销级别
		$Data=array(
			"Users_ID"=>$Users_ID,
			"Level_Name"=>"普通分销商",
			"Level_LimitType"=>3,
			"Level_PeopleLimit"=>json_encode(array(1=>'0'),JSON_UNESCAPED_UNICODE),
			"Level_CreateTime"=>time()
		);
		$Add=$DB->Add('distribute_level',$Data);
		update_dis_level($DB,$Users_ID);
		
		//循环设置各功能模块	
		$Permit=array(
			"shop"=>"微商城",
			"user"=>"会员中心",
			"scratch"=>"刮刮卡",
			"fruit"=>"水果达人",
			"turntable"=>"欢乐大转盘",
			"battle"=>"一战到底"
		);
		foreach($Permit as $k=>$v){
			//根据授权的功能模块添加素材
			$Material=array(
				"Title"=>$v,
				"ImgPath"=>"/static/api/images/cover_img/".$k.".jpg",
				"TextContents"=>"",
				"Url"=>"/api/".$Users_ID."/".$k."/"
			);
			$Data=array(
				"Users_ID"=>$Users_ID,
				"Material_Table"=>$k,
				"Material_TableID"=>0,
				"Material_Display"=>0,
				"Material_Type"=>0,
				"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE),
				"Material_CreateTime"=>time()
			);
			$Add=$DB->Add("wechat_material",$Data);
			$flag=$flag&&$Add;
			//添加关键词自动回复功能,并将素材id对应进去
			$Data=array(
				"Users_ID"=>$Users_ID,
				"Reply_Table"=>$k,
				"Reply_TableID"=>0,
				"Reply_Display"=>0,
				"Reply_Keywords"=>$v,
				"Reply_PatternMethod"=>0,
				"Reply_MsgType"=>1,
				"Reply_MaterialID"=>$DB->insert_id(),
				"Reply_CreateTime"=>time()
			);
			$Add=$DB->Add("wechat_keyword_reply",$Data);
			$flag=$flag&&$Add;
		}
		if($flag){
			//执行事务
			mysql_query("commit");
			echo '<script language="javascript">
		if(confirm("增加成功！您还要继续增加用户吗？")){
			window.open("Add.php","_self");
		}else{
			window.open("index.php","_self");
		}
		</script>';
			exit();
		}else{
			//失败回滚
			mysql_query("roolback");
			echo '<script language="javascript">alert("添加失败！");history.go(-1);</script>';
			exit();
		}
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
<script charset="utf-8" src="/third_party/My97DatePicker/WdatePicker.js"></script>
<style>
.right_top{font-size:14px; font-weight:bold; height:36px; line-height:36px; padding-left:15px; background:#fff}
.right_ul{padding-left:5px; padding-top:10px; background:#fff; list-style:none; margin:0px}
.right_ul li{height:28px; line-height:28px;}
</style>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
		<ul>
        <li><a href="index.php">商家管理</a></li>
        <li class="cur"><a href="add.php">添加商家</a></li>
		<li><a href="sjrz.php">入驻申请</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
	 <form class="r_con_form" method="post" action="?">
	 <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
       <td valign="top">
        
            <div class="rows">
                <label>登录帐号</label>
                <span class="input"><input type="text" name="Users_Account" class="form_input" /> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>登录密码</label>
                <span class="input"><input type="password" name="Users_PasswordA" class="form_input" /> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>确认密码</label>
                <span class="input"><input type="password" name="Users_PasswordB" class="form_input" /> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>到期时间</label>
                <span class="input">
                    <input type="text" name="Users_ExpireDate" style="Width:150px;" value="<?php echo date("Y-m-d H:i:s",(time()+86400*7)); ?>" onClick="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly>
                </span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>是否启用</label>
                <span class="input">
                    <label><input name="Users_Status" type="radio" value="1" checked>启用</label>
                    <label><input name="Users_Status" type="radio" value="0">禁用</label>
                </span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>所属行业</label>
                <span class="input">
                 <select name="Users_Industry">
                   <?php
				   $lists = array();
                   $DB->get("industry","*","where parentid=0 order by id asc");
				   while($r=$DB->fetch_assoc()){
					   $lists[] = $r;
				   }
				   foreach($lists as $r){
					    echo '<option value="'.$r["id"].'">'.$r["name"].'</option>';
					   	$DB->get("industry","*","where parentid=".$r["id"]." order by id asc");
						while($t=$DB->fetch_assoc()){
							echo '<option value="'.$t["id"].'">&nbsp;└&nbsp;'.$t["name"].'</option>';
						}
				   }
			       ?>
                 </select>
                </span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>商家简介</label>
                <span class="input"><textarea id="Users_Notes" name="Users_Notes" rows="5" style="width:200px"></textarea></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" name="Submit" value="确定" class="submit">
                  <input type="reset" value="重置"></span>
                <div class="clear"></div>
            </div>
         
		</td>
		<td width="10">&nbsp;</td>
        <td width="240" style="border-left:1px #dddddd solid; padding:10px" valign="top">
         <div class="right_top">开通权限</div>
         <ul class="right_ul">
         <?php foreach($right as $key=>$value){?>
		   <?php foreach($file[$value] as $k=>$v){?>
          	<li><input type="checkbox" name="JSON[<?php echo $value;?>][]" id="Right_<?php echo $k;?>" value="<?php echo $k;?>" onClick="Set_Price(0,'<?php echo $k;?>');" checked> <?php echo $v;?></li>
		   <?php }?>
         <?php }?>
         </ul>
        </td>
	   </tr>
      </table>
      </form>	  
    </div>
  </div>
</div>
<SCRIPT type=text/javascript>
function Set_Price(p,k){
	if(document.getElementById("Right_"+k).checked==false){
		document.getElementById("Right_"+k).value = "";
	}else{
		document.getElementById("Right_"+k).value = k;
	}
}
</SCRIPT>
</body>
</html>
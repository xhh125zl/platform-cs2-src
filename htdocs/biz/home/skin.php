<?php
require_once('../global.php');
if($IsStore==0){
	echo '<script language="javascript">alert("请勿非法操作！");history.back();</script>';
	exit();
}
if(isset($_GET["action"])){
	if($_GET["action"]=="set"){		
		$Data=array(
			"Skin_ID"=>$_GET["Skin_ID"]
		);
		$flag=$DB->Set("biz",$Data,"where Biz_ID=".$_SESSION["BIZ_ID"]);
		$r = $DB->GetRs("biz_skin","Skin_Json","where Skin_ID=".$_GET["Skin_ID"]);
		if(!empty($r["Skin_Json"])){
		    $rs=$DB->GetRs("biz_home","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Skin_ID=".$_GET["Skin_ID"]);
			if(empty($rs)){
				$Data=array(
					"Users_ID"=>$rsBiz["Users_ID"],
					"Biz_ID"=>$_SESSION["BIZ_ID"],
					"Skin_ID"=>$_GET["Skin_ID"],
					"Home_Json"=>$r["Skin_Json"]
				);
				$flag=$DB->Add("biz_home",$Data);
			}
		}
		if($flag){			
			echo '<script language="javascript">alert("选择成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("操作失败");history.back();</script>';
		}
	}
	exit;
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
<SCRIPT type=text/javascript>
function SelectSkinID(Skin_ID){
	if(confirm("您确定要选择此模板吗？")){
		location.href="skin.php?action=set&Skin_ID="+Skin_ID;
	}
}
</SCRIPT>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="skin.php">模板风格</a></li>
		<li><a href="home.php">首页设置</a></li>
      </ul>
    </div>
    <div id="skin" class="r_con_wrap">
      <div class="list">
        <ul>
          <?php
		    $DB->get("biz_skin","*","order by Skin_ID asc");
			while($rsSkin=$DB->fetch_assoc()){
				echo '<li class="'.($rsBiz['Skin_ID']==$rsSkin['Skin_ID']?"cur":"").'">
				<div class="item" title="点击选择店铺风格" onClick="SelectSkinID('.$rsSkin['Skin_ID'].');">
				  <div class="img"><img src="'.$rsSkin['Skin_ImgPath'].'" /></div>
				  <div class="title">'.$rsSkin['Skin_Name'].'</div>
				</div>
			  </li>';
			}
		  ?>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
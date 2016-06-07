<?php 
$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
$rsKeyword=$DB->GetRs("wechat_keyword_reply","*","where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='shop' and Reply_TableID=0 and Reply_Display=0");
$json=$DB->GetRs("wechat_material","*","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='shop' and Material_TableID=0 and Material_Display=0");
$rsMaterial=json_decode($json['Material_Json'],true);

$man_list = json_decode($rsConfig['Man'],true);
$integral_use_laws = json_decode($rsConfig['Integral_Use_Laws'],true);


if($_POST)
{
	
	$flag=true;
	$msg="";
	
	$man = array();
	if(isset($_POST['man_reach'])){
		$man_reach = $_POST['man_reach'];
		$man_award  = $_POST['man_award'];
		foreach($man_reach as $key=>$item){
			$man[] = array('reach'=>$item,'award'=>$man_award[$key]);
		}
	}
	
	$Integral_Use_Laws = array();
	
	if(!empty($_POST['Integral_Man'])){
		$integral_man = $_POST['Integral_Man'];
		$integral_use = $_POST['Integral_Use'];
		
		foreach($integral_man as $key=>$item){
			$Integral_Use_Laws[] = array("man"=>$item,"use"=>$integral_use[$key]);
		}	
	}
	
	
	
	$Data=array(
		"Man"=>json_encode($man,JSON_UNESCAPED_UNICODE),
    	"Integral_Convert"=>isset($_POST["Integral_Convert"])?$_POST["Integral_Convert"]:0,
		"Integral_Buy"=>isset($_POST["Integral_Buy"])?$_POST["Integral_Buy"]:0,
		"Integral_Use_Laws"=>json_encode($Integral_Use_Laws,JSON_UNESCAPED_UNICODE),
		
	);
	
	
	$Flag =$DB->Set("shop_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	
	if($Flag){
		$response =array(
			"status"=>1,
			"url"=>$_SERVER['HTTP_REFERER'].'?t='.time(),
			"msg"=>"保存成功，继续修改？"
		);
	}else{
	   $response = array(
			"status"=>0,
			"msg"=>"保存失败"
		);
	}
	

	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
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
 <script type='text/javascript' src='/static/member/js/shop.js'></script>
<script type="text/javascript" src="/third_party/uploadify/jquery.uploadify.min.js"></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
   
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class=""><a href="skin.php">风格设置</a></li>
        <li class=""><a href="home.php">首页设置</a></li>
        <li><a href="menu_config.php">菜单配置</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">$(document).ready(function(){
		
		global_obj.config_form_init();
		shop_obj.confirm_form_init();
		
	});</script>
    <div class="r_con_config r_con_wrap">
<div class="r_con_config">
	<div class="shopping">
	  <form id="config_form" class="r_con_form" name="other_config_form" action="ohter_config.php" method="post">
		<div class="rows">
          <label>满多少减多少</label>
          <span class="input">
			<a href="javascript:void(0);" id="add_man" class="red">添加</a>
			<ul id="man_panel">
            <?php if(count($man_list)>0):?>
				<?php foreach($man_list as $key=>$man):?>
                <li class="item"> 满：￥
                <input name="man_reach[]" value="<?=$man['reach']?>" class="form_input" size="3" maxlength="10" type="text">
                减：￥
                <input name="man_award[]" value="<?=$man['award']?>" class="form_input" size="3" maxlength="10" type="text">
                <a><img src="/static/member/images/ico/del.gif" hspace="5"></a>
                </li>
                <?php endforeach; ?>
            <?php endif;?>
                <li class="clear"></li>
            </ul>
			<p>(订单满足多大金额，可以多少免单多少,例满1000元可免单80元,<br/>设置排序由大到小，例如，先设置满1000减200,再设置满500减80)</p>
          </span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>积分使用规则</label>
          <span class="input">
			<a href="javascript:void(0);" id="add_integral_law" class="red">添加</a>
			<ul id="integral_panel">                   
            <?php if(count($integral_use_laws)>0):?>
			<?php foreach($integral_use_laws as $key=>$law):?>
                <li class="item"> 满：￥
                    <input name="Integral_Man[]" value="<?=$law['man']?>" class="form_input" size="3" maxlength="10" type="text"> 可用
                    <input name="Integral_Use[]" value="<?=$law['use']?>" class="form_input" size="3" maxlength="10" type="text"> 个
                    <a><img src="/static/member/images/ico/del.gif" hspace="5"></a>
                </li>
            <?php endforeach; ?>
            <?php endif;?>
                <li class="clear"></li>
            </ul>
            <p>(订单满足多大金额，可以使用多少个积分,例满100元可使用100个积分)</p>
          </span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>积分设置</label>
          <span class="input">
          每 <input type="text" name="Integral_Convert" size="5" value="<?=$rsConfig['Integral_Convert']?>"/> 元，赠送一分
		  </span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>积分抵用设置</label>
          <span class="input">
          每 <input type="text" name="Integral_Buy" size="5" value="<?=$rsConfig['Integral_Buy']?>"/> 积分抵用一元
		  </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="提交保存" name="submit_btn">
		  </span>
          <div class="clear"></div>
        </div>
	  </form>
	</div>
</div>
</div>
</div>
</body>
</html>
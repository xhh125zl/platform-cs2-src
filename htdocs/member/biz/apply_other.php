<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

$bizConfig=$DB->GetRs("biz_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
$year_list = json_decode($bizConfig['year_fee'],true);

if($_POST){
 //echo "<pre>";print_R($_POST);die;
    if (!isset($_POST['year_fee'])) {
       echo '<script language="javascript">alert("请增加年费设置");history.back();</script>'; 
       exit;
    }
    if (empty($_POST['year_fee']['name'])) {
        echo '<script language="javascript">alert("请填写年费设置");history.back();</script>'; 
        exit;
    }
    if (empty($_POST['year_fee']['value'])) {
        echo '<script language="javascript">alert("请填写年费设置");history.back();</script>'; 
        exit;
    }
    foreach ($_POST['year_fee']['name'] as $k => $v ) {
        if (!is_numeric($v) || $v < 0) {
            echo '<script language="javascript">alert("时间为大于0的数字");history.back();</script>'; 
            exit;
        }
    }
    foreach ($_POST['year_fee']['value'] as $k => $v ) {
        if (!is_numeric($v) || $v < 0) {
            echo '<script language="javascript">alert("费用为大于0的数字");history.back();</script>'; 
            exit;
        }
    }
 
    $year_fee = $_POST['year_fee'];
	$Data = array(
		"year_fee"=>json_encode($year_fee,JSON_UNESCAPED_UNICODE)	 
	);
		
	$Flag=$DB->Set("biz_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	if($Flag){
		echo '<script language="javascript">alert("编辑成功");window.location="apply_other.php";</script>';
	}else{
		echo '<script language="javascript">alert("编辑失败");history.back();</script>';
	}
	exit;
} 
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">

<link href='/static/css/global.css' rel='stylesheet' type='text/css' />

<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/products_attr_helper.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>


KindEditor.ready(function(K) {
	K.create('textarea[name="BaoZhengJin"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=web_column&UsersID=<?php echo $_SESSION['Users_ID'];?>',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
	
	});
	
	K.create('textarea[name="NianFei"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=web_column&UsersID=<?php echo $_SESSION['Users_ID'];?>',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
	
	});
	
	K.create('textarea[name="JieSuan"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=web_column&UsersID=<?php echo $_SESSION['Users_ID'];?>',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
	
	});
})
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
    <script type='text/javascript' src='/static/member/js/biz.js'></script>
    <script language="javascript">$(document).ready(biz_obj.group_edit);</script>
    <div class="r_nav">
      <ul>
	<li class=""><a href="apply_config.php">入驻描述设置</a></li>
	<li class=""><a href="reg_config.php">注册页面设置</a></li>
        <li class="cur"><a href="apply_other.php">年费设置</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <div class="r_con_config">
	<div class="shopping">
	  <form id="config_form" class="r_con_form" name="other_config_form" action="apply_other.php" method="post">
		<div class="rows">
          <label>年费设置</label>
          <span class="input">
                <a href="javascript:void(0);" id="add_man" class="red">添加</a><span class="tips">&nbsp;&nbsp;&nbsp;&nbsp;（当商城设置商家发布自营产品需要收费时,此设置才会有效）</span>
                <ul id="man_panel">
                <?php if(count($year_list['name'])>0):?>
                    <?php foreach($year_list['name'] as $key=>$year):?>
                    <li class="item"> 时间：
                        <input name="year_fee[name][]" value="<?=$year?>" class="form_input" size="20" maxlength="10" type="text">年&nbsp;&nbsp;
                       费用
                        <input name="year_fee[value][]" value="<?=$year_list['value']["$key"]?>" class="form_input" size="15" maxlength="10" type="text">元
                        <a><img src="/static/member/images/ico/del.gif" hspace="5"></a>
                    </li>
                    <?php endforeach; ?>
                <?php endif;?>
                    <li class="clear"></li>
                </ul>
		 
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
</div>
    <script>
    
    
		$("#add_man").click(function(){
			var li_item = '<li class="item">时间： <input name="year_fee[name][]" value="" class="form_input" size="20" maxlength="10" type="text">年&nbsp;&nbsp;费用 <input name="year_fee[value][]" value="" class="form_input" size="15" maxlength="10" type="text"> 元<a><img src="/static/member/images/ico/del.gif" hspace="5"></a></li>';
			$("ul#man_panel").append(li_item);
		});
		
		$("#man_panel li.item a").live('click',function(){
				$(this).parent().remove();
		});
		</script>
</body>
</html>
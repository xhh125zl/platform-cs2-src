<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/General_Tree.php');

$DB->showErr=false;

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
//获取行业列表
$rsIndustries = $DB->get("industry","id,name,parentid");
$industry_list = $DB->toArray($rsIndustries);

//实例化通用树类
$param = array('result'=>$industry_list,'fields'=>array('id','parentid'));
$generalTree = new General_tree($param);
//生成分类树
$industryTree = $generalTree->leaf();

foreach($industry_list as $key=>$item){
	$industry_dropdown[$item['id']] = $item;
}
  
//获取商铺配置
$rsConfig = $DB->GetRs("users","Users_ID,Users_Company,Users_Industry,Users_Logo","where Users_ID='".$_SESSION["Users_ID"]."'");


if($_POST)
{
	//开始事务定义
	$flag=true;
	
	
	$data = array(
			"Users_Company"=>$_POST['Users_Company'],
			"Users_Industry"=>$_POST['Users_Industry'],
			"Users_Logo"=>$_POST['Users_Logo']);
	
	$flag = $DB->set("users",$data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	
	if($flag)
	{
	
		$Data=array(
			"status"=>1,
			"url"=>$_SERVER['HTTP_REFERER'].'?t='.time(),
			"msg"=>"保存成功，继续修改？"
		);
	}else
	{
	
		$Data=array(
			"status"=>0,
			"msg"=>"保存失败"
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
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
<style type='text/css'>
#ImgDetail img{width:60px; height:60px;}
</style>  

<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
   
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="info.php">商家信息</a></li>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class=""><a href="other_config.php">活动设置</a></li>
        <li class=""><a href="skin.php">风格设置</a></li>
        <li class=""><a href="home.php">首页设置</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">$(document).ready(function(){
		
		global_obj.config_form_init();
		global_obj.file_upload($('#ImgUpload'), $('#config_form input[name=Users_Logo]'), $('#ImgDetail'));
		
	});</script>
    <div class="r_con_config r_con_wrap">
      <form id="config_form" action="config.php" method="post" class="r_con_form">
        <div class="rows">
          <label>商家名称</label>
          <span class="input">
          <input type="text" class="input" name="Users_Company" value="<?=$rsConfig['Users_Company']?>" maxlength="100" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>所在行业</label>
          <span class="input">
			<select name="Users_Industry" id="Users_Industry">
                <?php foreach($industryTree as $key=>$item):?>
	            <optgroup label="<?=$item['name']?>">
                <?php if(isset($item['child'])):?>	
                <?php foreach($item['child'] as $k=>$v):?>
                <option value="<?=$v['id']?>" <?php if($v['id'] == $rsConfig['Users_Industry']){echo 'selected';} ?>><?=$v['name']?></option>
				<?php endforeach;?>
                <?php endif;?>
                </optgroup>
				<?php endforeach;?>
            </select>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>商家形象图</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input id="ImgUpload" name="ImgUpload" type="button" style="width:80px" value="上传图片">
              <input type="hidden" id="Users_Logo" name="Users_Logo" value="<?=$rsConfig["Users_Logo"]?>" />
            </div>
            <div class="tips">大图尺寸建议：60*60px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail"><?php echo empty($rsConfig["Users_Logo"])?"":'<img src="'..$rsConfig["Users_Logo"].'" />'; ?></div>
          </span> </span>
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
</body>
</html>
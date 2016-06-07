<?php
ini_set("display_errors","On");

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

$max_level = 9;
$base_url = base_url();

if (!empty($_POST)) 
{
  $s = json_encode(array('myterm'=>$_POST['my_term'], 'childlevelterm'=>$_POST['child_level_term'], 'catcommission' => $_POST['catcommission'], 'cattuijian' => $_POST['cattuijian'], 'childtuijian' => $_POST['childtuijian']), JSON_UNESCAPED_UNICODE);
  $Flag = $DB->query('UPDATE distribute_config SET `Index_Professional_Json`="' .mysql_real_escape_string($s). '"');
  if($Flag){
    echo '<script language="javascript">alert("设置成功");window.location="setting_distribute.php";</script>';
  }else{
    echo '<script language="javascript">alert("保存失败");history.back();</script>';
  }
  exit;
}

$rsAccount = $DB->GetRs('distribute_config', 'Dis_Mobile_Level, Index_Professional_Json', ' WHERE `Users_ID`="' .$_SESSION["Users_ID"]. '"');

if (!empty($rsAccount['Index_Professional_Json'])) 
{
  $Index_Professional_Json = json_decode($rsAccount['Index_Professional_Json'], TRUE);
} else {
  $Index_Professional_Json = array(
    'myterm' => '我的团队',
    'childlevelterm' => array(1 => '一级分销商', 2 => '二级分销商', 3 => '三级分销商',
                         4 => '四级分销商',5=>'五级分销商',6=>'六级分销商',
             7=>'七级分销商',8=>'八级分销商',9=>'九级分销商'),
    'catcommission' => '佣金',
    'cattuijian' => '我的推荐人',
    'childtuijian' => array(1 => '直接推荐人', 2 => '二级推荐人', 3 => '三级推荐人')
  );
}

$level_name_list = array(1 => '一级分销商', 2 => '二级分销商', 3 => '三级分销商',
                         4 => '四级分销商',5=>'五级分销商',6=>'六级分销商',
						 7=>'七级分销商',8=>'八级分销商',9=>'九级分销商');

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/distribute/config.js'></script>
<script type="text/javascript">
$(document).ready(config_obj.base_config);
</script>
<style type="text/css">
#level_intro table{margin:8px 0px; border:1px #dfdfdf solid}
#level_intro table th{height:30px; line-height:30px; background:#f5f5f5; border-right:1px #dfdfdf solid}
#level_intro table td{padding:8px 0px; line-height:20px; border-right:1px #dfdfdf solid; text-align:center; border-top:1px #dfdfdf solid; background:#FFF}
#level_intro .last{border-right:none}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li><a href="setting.php">分销设置</a></li>
        <li><a href="setting_withdraw.php">提现设置</a></li>
        <li><a href="setting_other.php">其他设置</a></li>
        <li><a href="setting_protitle.php">爵位设置</a></li>
        <li class="cur"><a href="setting_distribute.php">分销首页设置</a></li>
      </ul>
    </div>
    <div class="r_con_wrap">
      <form id="distribute_config_form" class="r_con_form" method="post" action="?">
        <input type="hidden" id="level_has" value="">
        <input type="hidden" id="level" value="">
        
        <div class="rows">
        	<label>管理称呼</label>
            <span class="input">
           		 <input type="text" name="my_term" class="form_input" value="<?php echo $Index_Professional_Json['myterm']; ?>">
            </span>
            <div class="clear"></div>
        </div>

        <div class="rows">
          <label>分销商等级称呼</label>
            <span class="input">
              <?php if (!empty($rsAccount['Dis_Mobile_Level']) && $rsAccount['Dis_Mobile_Level'] > 0) : ?>
              <?php for ($i = 1; $i <= $rsAccount['Dis_Mobile_Level']; $i++) { ?>
               <input type="text" name="child_level_term[<?php echo $i; ?>]" class="form_input" value="<?php echo empty($Index_Professional_Json['childlevelterm'][$i]) ? $level_name_list[$i] : $Index_Professional_Json['childlevelterm'][$i]; ?>">
              <?php } ?>
              <?php endif; ?>
            </span>
            <div class="clear"></div>
        </div>

        <div class="rows">
          <label>佣金称呼</label>
            <span class="input">
               <input type="text" name="catcommission" class="form_input" value="<?php echo $Index_Professional_Json['catcommission']; ?>">
            </span>
            <div class="clear"></div>
        </div>

        <div class="rows">
          <label>推荐人大类名称</label>
            <span class="input">
               <input type="text" name="cattuijian" class="form_input" value="<?php echo $Index_Professional_Json['cattuijian']; ?>">
            </span>
            <div class="clear"></div>
        </div>

        <div class="rows">
          <label>推荐人称呼</label>
            <span class="input">
            <?php if (!empty($Index_Professional_Json['childtuijian']) && $Index_Professional_Json['childtuijian'] > 0) : ?>
            <?php for ($i = 1; $i <= count($Index_Professional_Json['childtuijian']); $i++) { ?>
               <input type="text" name="childtuijian[<?php echo $i; ?>]" class="form_input" value="<?php echo $Index_Professional_Json['childtuijian'][$i]; ?>">
            <?php } ?>
            <?php endif; ?>
            </span>
            <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          </span>
          <div class="clear"></div>
        </div> 
      </form>
    </div>
  </div>
</div>
</body>
</html>
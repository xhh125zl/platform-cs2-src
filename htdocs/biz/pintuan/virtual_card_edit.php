<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
$CardId=empty($_REQUEST['CardId'])?0:$_REQUEST['CardId'];

$rsCart = $DB->GetRs("pintuan_virtual_card","*","where Users_ID='{$UsersID}' and Card_Id=".$CardId);

if ($_POST) {
	$Data = array(
		'Card_Name' => $_POST['Card_Name'],
		'Card_Password' => $_POST['Card_Password'],
		'Card_Description' => $_POST['Card_Description'],
		'Card_UpadteTime' => time()
		);

	$Flag = $DB->set('pintuan_virtual_card', $Data, "where Users_ID='{$UsersID}' and Card_Id=".$CardId);
	if($Flag)
	{
		echo '<script language="javascript">alert("修改成功");window.location="virtual_card.php";</script>';
	} else {
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
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
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/weicbd.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/weicbd.js'></script>
    <script language="javascript">$(document).ready(weicbd_obj.mulu_edit_init);</script>
    <div class="r_nav">
      <ul>
        <li><a href="virtual_card.php">卡密列表</a></li>
        <li class="cur"><a href="virtual_card_edit.php">编辑卡密</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <div class="category">
        <div class="m_righter" style="margin-left:0px;">
          <form action="?" method="post" id="mulu_edit">
            <h1>添加虚拟卡密</h1>
            
            <div class="opt_item">
              <label>虚拟卡号：</label>
              <span class="input">
              <input type="text" name="Card_Name" value="<?php echo $rsCart['Card_Name']; ?>" class="form_input" size="30" maxlength="30" notnull />
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>

            <div class="opt_item">
              <label>卡密码：</label>
              <span class="input">
              <input type="text" name="Card_Password" value="<?php echo $rsCart['Card_Password']; ?>" class="form_input" size="30" maxlength="30" notnull />
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            
          	<div class="opt_item">
	          <label>备注卡号</label>
	          <span class="input">
	          	<textarea name="Card_Description" class="Attr_Group"><?php echo $rsCart['Card_Description']; ?></textarea>
	          </span>
	          <br/>
	         
	          <div class="clear"></div>
	           每行一个商品属性组。排序也将按照自然顺序排序。
	        </div>
          
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="hidden" name="CardId" value="<?php echo $rsCart['Card_Id']; ?>">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="修改" />
              <a href="javascript:void(0);" class="btn_gray" onClick="location.href='virtual_card.php'">返回</a></span>
              <div class="clear"></div>
            </div>
          </form>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
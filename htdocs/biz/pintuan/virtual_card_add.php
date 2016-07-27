<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if (!empty($_POST)) {
	if (empty($_POST['Type_Id'])) {
		echo '<script language="javascript">alert("类型不能为空");window.location="virtual_card.php";</script>';
	}
	$Data = array(
		'Card_Name' => htmlentities($_POST['Card_Name']),
		'Card_Password' => htmlentities($_POST['Card_Password']),
		'Card_Description' => htmlentities($_POST['Card_Description']),
		'Card_CreateTime' => time(),
		'Card_UpadteTime' => time(),
        'Type_Id' => (int)$_POST['Type_Id'],
        'Users_ID' => $UsersID,
	    'Biz_Id' => $BizID
		);

	$Flag = $DB->Add("pintuan_virtual_card",$Data);

	if ($Flag) {
		echo '<script language="javascript">alert("添加成功");window.location="virtual_card.php";</script>';
	} else {
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
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
    <link href='/static/member/css/weicbd.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/weicbd.js'></script>
    <script language="javascript">$(document).ready(weicbd_obj.mulu_edit_init);</script>
    <div class="r_nav">
      <ul>
        <li><a href="virtual_card.php">虚拟卡密列表</a></li>
        <li class="cur"><a href="virtual_card_add.php">添加卡密</a></li>
        <li><a href="virtual_card_type.php">卡密类型列表</a></li>
        <li><a href="virtual_card_type_add.php">添加卡密类型</a></li>
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
              <input type="text" name="Card_Name" value="" class="form_input" size="30" maxlength="30" notnull />
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>

            <div class="opt_item">
              <label>卡密码：</label>
              <span class="input">
              <input type="text" name="Card_Password" value="" class="form_input" size="30" maxlength="30" notnull />
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>

            <div class="opt_item">
              <label>卡类型：</label>
              <span class="input">
              
              <select name="Type_Id">
              <?php 
                $condition = "WHERE `Users_ID` = '{$UsersID}'";
                $Type = $DB->Get("pintuan_virtual_card_type","*",$condition);
                while ($r=$DB->fetch_assoc()) { $List[] = $r; }
                if (!empty($List)) {
                foreach ($List as $k => $v) {
              ?>
                <option value="<?php echo $v['Type_Id']; ?>"><?php echo $v['Type_Name']; ?></option>
              <?php }} ?>
              </select>
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            
          	<div class="opt_item">
	          <label>备注卡号</label>
	          <span class="input">
	          	<textarea name="Card_Description" class="Attr_Group"></textarea>
	          </span>
	          <br/>
	         
	          <div class="clear"></div>
	           每行一个商品属性组。排序也将按照自然顺序排序。
	        </div>
          
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="添加" />
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
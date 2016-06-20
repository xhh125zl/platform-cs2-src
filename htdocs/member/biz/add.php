<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if($_POST){
	$_POST["Account"] = trim($_POST["Account"]);
	if(empty($_POST["Account"])){
		echo '<script language="javascript">alert("商家登录账号不能为空！");history.back();</script>';
		exit();
	}else{
		$r = $DB->GetRs("biz","*","where Biz_Account='".$_POST["Account"]."'");
		if($r){
			echo '<script language="javascript">alert("此登录账号已被注册，请更改！");history.back();</script>';
			exit();
		}
	}
	if(empty($_POST['GroupID'])){
		echo '<script language="javascript">alert("请选择商家所属分组！");history.back();</script>';
		exit();
	}
	if(empty($_POST["PassWord"])){
		echo '<script language="javascript">alert("登录密码不能为空！");history.back();</script>';
		exit();
	}elseif($_POST["PassWord"]!=$_POST["PassWordA"]){
		echo '<script language="javascript">alert("登录密码与确认密码不相同！");history.back();</script>';
		exit();
	}
	
	if($_POST["FinanceType"]==0){
		if(!is_numeric($_POST["FinanceRate"]) || $_POST["FinanceRate"]<=0){
			echo '<script language="javascript">alert("网站比例必须大于零！");history.back();</script>';
			exit();
		}
	}
        if(!empty($_POST["PaymenteRate"])){
		if(!is_numeric($_POST["PaymenteRate"]) || $_POST["PaymenteRate"]<=0){

			echo '<script language="javascript">alert("结算比例必须大于零！");history.back();</script>';
			exit();
		}
	}
	$_POST['Introduce'] = htmlspecialchars($_POST['Introduce'], ENT_QUOTES);
	$Data=array(
		"Biz_Account"=>$_POST['Account'],
		"Biz_PassWord"=>md5($_POST['PassWord']),
		"Biz_Name"=>$_POST['Name'],
		"Group_ID"=>$_POST['GroupID'],
		"Biz_Address"=>$_POST['Address'],
		"Biz_Homepage"=>$_POST['Homepage'],
		"Biz_Introduce"=>$_POST['Introduce'],
		"Biz_Contact"=>$_POST['Contact'],
		"Biz_Phone"=>$_POST['Phone'],
		"Biz_Email"=>$_POST['Email'],
		"Biz_Status"=>$_POST['Status'],
		"Biz_CreateTime"=>time(),
		"Biz_SmsPhone"=>$_POST['SmsPhone'],
		"Skin_ID"=>1,
		"Finance_Type"=>$_POST["FinanceType"],
		"Finance_Rate"=>empty($_POST["FinanceRate"]) ? 0 : $_POST["FinanceRate"],

                "PaymenteRate"=>empty($_POST["PaymenteRate"]) ? 100 : $_POST["PaymenteRate"],
		"Users_ID"=>$_SESSION["Users_ID"],
		"Biz_Logo"=>$_POST['LogoPath'],
                "Invitation_Code"=>isset($_POST['Invitation_Code'])?trim($_POST['Invitation_Code']):''

	);
	$Flag=$DB->Add("biz",$Data);
	$bizid = $DB->insert_id();
	
	$r = $DB->GetRs("biz_skin","Skin_Json","where Skin_ID=1");
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Biz_ID"=>$bizid,
		"Skin_ID"=>1,
		"Home_Json"=>$r["Skin_Json"]
	);
	$DB->Add("biz_home",$Data);
	
	if($Flag){
		echo '<script language="javascript">alert("添加成功");window.location="index.php";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}else{
	//商家分组
	$groups = array();
	$DB->Get("biz_group","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Group_index asc, Group_ID asc");
	while($r=$DB->Fetch_assoc()){
		$groups[$r["Group_ID"]] = $r;
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
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	K.create('textarea[name="Introduce"]', {
        themeType : 'simple',
		filterMode : false,
        uploadJson : '/member/upload_json.php?TableField=biz&UsersID=<?php echo $_SESSION["Users_ID"];?>',
        fileManagerJson : '/member/file_manager_json.php',
        allowFileManager : true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
    });
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=biz&UsersID=<?php echo $_SESSION["Users_ID"];?>',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#LogoUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#LogoPath').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#LogoPath').val(url);
					K('#LogoDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})
</script>
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
      <script type='text/javascript' src='/static/member/js/biz.js'></script>
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <script language="javascript">$(document).ready(biz_obj.group_edit);</script>
      <form class="r_con_form" method="post" action="?" id="group_edit">
        <div class="rows">

          <label>邀请码</label>
          <span class="input">
          <input type="text" name="Invitation_Code" value="<?=isset($res)?$res['Invitation_Code']:'';?>" class="form_input" size="35" maxlength="50" />
          <font class="fc_red"></font></span>
          <div class="clear"></div>
        </div>  
        <div class="rows">

          <label>登录账号</label>
          <span class="input">
          <input type="text" name="Account" value="" class="form_input" size="35" maxlength="50" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>登录密码</label>
          <span class="input">
          <input type="password" name="PassWord" value="" class="form_input" size="35" maxlength="50" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>确认登录密码</label>
          <span class="input">
          <input type="password" name="PassWordA" value="" class="form_input" size="35" maxlength="50" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>所属分组</label>
          <span class="input">
          <select name="GroupID" notnull>
          <?php foreach($groups as $GroupID=>$v){?>
          <option value="<?php echo $GroupID;?>"><?php echo $v["Group_Name"];?></option>
          <?php }?>
          </select>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商家名称</label>
          <span class="input">
          <input type="text" name="Name" value="" class="form_input" size="35" maxlength="50" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>商家地址</label>
          <span class="input">
          <input name="Address" id="Address" value="" type="text" class="form_input" size="40" maxlength="100" notnull> <font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商家logo</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="LogoUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片建议尺寸：100*100px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="LogoDetail" style="margin-top:8px"></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>接受短信手机</label>
          <span class="input">
          <input type="text" name="SmsPhone" value="" class="form_input" size="30" pattern="[0-9]*" notnull/>
          <font class="fc_red">*</font> <span class="tips">当用户下单时，系统会自动发短信到该手机</span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>联系人</label>
          <span class="input">
          <input type="text" name="Contact" value="" class="form_input" size="35" notnull/>
          <font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>联系电话</label>
          <span class="input">
          <input type="text" name="Phone" value="" class="form_input" size="35" notnull/>
          <font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>电子邮箱</label>
          <span class="input">
          <input type="text" name="Email" value="" class="form_input" size="35"/>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>公司主页</label>
          <span class="input">
          <input type="text" name="Homepage" value="" class="form_input" size="35" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商家简介</label>
          <span class="input">
          <textarea class="ckeditor" name="Introduce" style="width:600px; height:300px;"></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>财务结算类型</label>
          <span class="input">
              <input type="radio" name="FinanceType" value="0" id="FinanceType_0" onClick="$('#FinanceRate').show();" checked /><label for="FinanceType_0"> 按交易额比例</label>&nbsp;&nbsp;<input type="radio" name="FinanceType" value="1" id="FinanceType_1" onClick="$('#FinanceRate').hide();" /><label for="FinanceType_1"> 具体产品设置</label><br />
          <span class="tips">注：若按交易额比例，则网站提成为：产品售价*比例%，网站提成包含网站应得和佣金发放两项</span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="FinanceRate">
          <label>网站提成</label>
          <span class="input">
          <input type="text" name="FinanceRate" value="" class="form_input" size="10" /> %
          </span>
          <div class="clear"></div>
        </div>

       <div class="rows" id="FinanceRate">
          <label>结算比例</label>
          <span class="input">
          <input type="text" name="PaymenteRate" value="" class="form_input" size="10" /> %
           <span class="tips">注：商家财务结算时,按照结算比例款项一部分转向商家指定的卡号,剩下的转入商家绑定的前台会员的余额中。</span>
          </span>
          <div class="clear"></div>
        </div>

        <div class="rows">
          <label>状态</label>
          <span class="input">
              <input type="radio" name="Status" value="0" id="Status_0" checked /><label for="Status_0">正常</label>&nbsp;&nbsp;<input type="radio" name="Status" value="1" id="Status_1" /><label for="Status_1">禁用</label>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" /></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" id="LogoPath" name="LogoPath" value="" />
      </form>
    </div>
  </div>
</div>
</body>
</html>
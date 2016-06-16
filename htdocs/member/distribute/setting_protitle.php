<?php
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$base_url = base_url();
if($_POST){	
    $distribute_config = Dis_Config::find($_SESSION["Users_ID"]);
	$Dis_List = array();
	$Dis_Pro_Title =  $_POST['Dis_Pro_Title'];
	
	foreach($Dis_Pro_Title['Name'] as $key=>$item){
		$Dis_List[$key+1]['Name'] = $item;
		$Dis_List[$key+1]['Consume'] = $Dis_Pro_Title['Consume'][$key];
		$Dis_List[$key+1]['Sales_Self'] = $Dis_Pro_Title['Sales_Self'][$key];
		$Dis_List[$key+1]['Sales_Group'] = $Dis_Pro_Title['Sales_Group'][$key];
		$Dis_List[$key+1]['Bonus'] = $Dis_Pro_Title['Bonus'][$key];
        $Dis_List[$key+1]['ImgPath'] = $Dis_Pro_Title['ImgPath'][$key];		
	}
	$distribute_config->Pro_Title_Status = $_POST['Pro_Title_Status'];
	$distribute_config->Pro_Title_Level = json_encode($Dis_List, JSON_UNESCAPED_UNICODE);
	$Flag = $distribute_config->save();
	if($Flag){
		echo '<script language="javascript">alert("设置成功");window.location.href="setting_protitle.php";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}else{
	$rsConfig = $DB->GetRs('distribute_config','Pro_Title_Level,Pro_Title_Status','where Users_ID="'.$_SESSION['Users_ID'].'"');
	if(!$rsConfig){
		$Data = array(
			'Users_ID'=>$_SESSION['Users_ID']
		);
		$DB->Add('distribute_config',$Data);
		$rsConfig = $DB->GetRs('distribute_config','Pro_Title_Level','where Users_ID="'.$_SESSION['Users_ID'].'"');
	}
	
	$dis_title_level = $rsConfig['Pro_Title_Level'] ? json_decode($rsConfig['Pro_Title_Level'],true) : array();
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
<script type='text/javascript' src='/static/js/jquery.formatCurrency-1.4.0.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/plugins/code/prettify.js"></script>
<script type='text/javascript' src="/static/member/js/distribute/config.js"></script>
<script>
KindEditor.ready(function(K) {
	editor = K.editor({
        uploadJson : '/member/upload_json.php?TableField=app_wedding',
        fileManagerJson : '/member/file_manager_json.php',
        showRemote : true,
        allowFileManager : true,
    });


	K('#ImgUpload_1').click(function(){	
	
        editor.loadPlugin('image', function(){
            editor.plugin.imageDialog({
                imageUrl : K('#ImgPath_1').val(),
                clickFn : function(url, title, width, height, border, align){
                    K('#ImgPath_1').val(url);
                    K('#ImgDetail_1').html('<img src="'+url+'" style="max-width:60px;"/>');
                    editor.hideDialog();
                }
            });
        });
	
    });
	
	K('#ImgUpload_2').click(function(){	
	
        editor.loadPlugin('image', function(){
            editor.plugin.imageDialog({
                imageUrl : K('#ImgPath_2').val(),
                clickFn : function(url, title, width, height, border, align){
                    K('#ImgPath_2').val(url);
                    K('#ImgDetail_2').html('<img src="'+url+'" style="max-width:60px;"/>');
                    editor.hideDialog();
                }
            });
        });
	
    });
	
	K('#ImgUpload_3').click(function(){	
	
        editor.loadPlugin('image', function(){
            editor.plugin.imageDialog({
                imageUrl : K('#ImgPath_3').val(),
                clickFn : function(url, title, width, height, border, align){
                    K('#ImgPath_3').val(url);
                    K('#ImgDetail_3').html('<img src="'+url+'" style="max-width:60px;"/>');
                    editor.hideDialog();
                }
            });
        });
	
    });
	
	K('#ImgUpload_4').click(function(){	
	
        editor.loadPlugin('image', function(){
            editor.plugin.imageDialog({
                imageUrl : K('#ImgPath_4').val(),
                clickFn : function(url, title, width, height, border, align){
                    K('#ImgPath_4').val(url);
                    K('#ImgDetail_4').html('<img src="'+url+'" style="max-width:60px;"/>');
                    editor.hideDialog();
                }
            });
        });
	
    });
})

$(document).ready(config_obj.protitle_config);
$(function(){
	$("#dis_pro_title_table tbody tr td img").css({"max-width":"60px"});
	$("#dis_pro_title_table tbody tr td img").click(function(){
		$(this).parent().next("input").val("");
		$(this).remove();
	});
});
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
     <ul>
    	<li><a href="setting.php">分销设置</a></li>
        <li><a href="setting_withdraw.php">提现设置</a></li>
        <li><a href="setting_other.php">其他设置</a></li>
        <li class="cur"><a href="setting_protitle.php">爵位设置</a></li>
		<li><a href="setting_distribute.php">分销首页设置</a></li>
     </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_config r_con_wrap">
	<span style="color:red;">(同时满足自身消费额、自身销售额和团队销售额所有条件方可晋级,最多可设四个级别,想设置几个级别，便可填写几条)</span>
  
    <div class="r_con_config r_con_wrap">
    <form method="post" action="?" id="distribute_config_form">
	   <table class="level_table" id="dis_pro_title_table" border="0" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <td width="10%">序号</td>
                    <td width="15%">称号名称</td>
                    <td width="15%">自身消费额<span style="color:red;">(仅可输入数字)</span></td>
                    <td width="15%">自身销售额<span style="color:red;">(仅可输入数字)</span></td>
                    <td width="15%">团队销售额<span style="color:red;">(仅可输入数字)</span></td>
                    <td width="15%">奖励比例<span style="color:red;">(%,占爵位奖励的百分比)</span></td>
                    <td width="15%">等级图标</td>
                   
                </tr>
            </thead>
            <tbody>
			
	
			<?php for($i=1;$i<=4;$i++){ ?>
			
				<tr  fieldtype="text">
                    <td><?=$i?></td>
                    <td>
                        <input class="form_input" value="<?php echo empty($dis_title_level[$i]['Name']) ? '' : $dis_title_level[$i]['Name'];?>" name="Dis_Pro_Title[Name][]" type="text">
                    </td>
                    <td>
                        <input class="form_input title_val" value="<?php echo empty($dis_title_level[$i]['Consume']) ? '' : $dis_title_level[$i]['Consume'];?>"  name="Dis_Pro_Title[Consume][]"  type="text">
                    </td>
					<td>
                        <input class="form_input title_val" value="<?php echo empty($dis_title_level[$i]['Sales_Self']) ? '' : $dis_title_level[$i]['Sales_Self'];?>"  name="Dis_Pro_Title[Sales_Self][]" type="text">
                    </td>
                    <td>
                    	 <input class="form_input Group_Num" value="<?php echo empty($dis_title_level[$i]['Sales_Group']) ? '' : $dis_title_level[$i]['Sales_Group'];?>"  name="Dis_Pro_Title[Sales_Group][]"
						
						 type="text">
                    </td>
                    <td>
                        <input class="form_input bonus" value="<?php echo empty($dis_title_level[$i]['Bonus']) ? '' : $dis_title_level[$i]['Bonus'];?>" name="Dis_Pro_Title[Bonus][]" 
 					type="text">
                    </td>
                 <td> 
                      <label>
                  <input type="button" id="ImgUpload_<?=$i?>" value="选择图片" style="width:80px;" />
                </label>
					<span class="pic" id="ImgDetail_<?=$i?>">
						<?php if(!empty($dis_title_level[$i]['ImgPath'])):?>
							<img src="<?=$dis_title_level[$i]['ImgPath']?>" />
						<?php endif;?>
					</span>
					
                <input type="hidden" id="ImgPath_<?=$i?>" name="Dis_Pro_Title[ImgPath][]" value="<?php echo empty($dis_title_level[$i]['ImgPath']) ? '' : $dis_title_level[$i]['ImgPath'];?>" />
                    </td>
				</tr>
			<?php } ?>
            <tr>
            	<td>
                	额度计入状态
                </td>
                <td colspan="6">
                	<input type="radio" name="Pro_Title_Status" id="status_2" value="2"<?php echo $rsConfig['Pro_Title_Status']==2 ? ' checked' : ''?>><label for="status_2">订单付款后计入</label>&nbsp;&nbsp;
                    <input type="radio" name="Pro_Title_Status" id="status_4" value="4"<?php echo $rsConfig['Pro_Title_Status']==4 ? ' checked' : ''?>><label for="status_4">订单确认收货后计入</label>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="blank20"></div>
        <div class="submit">
           <input name="submit_button" value="提交保存" type="submit">
 	    </div>
    </form>
  </div>
             
    </div>
  </div>
</div>
</body>
</html>
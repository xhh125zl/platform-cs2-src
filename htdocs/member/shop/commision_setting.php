<?php
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

if ($_POST) 
{
  unset($_POST['submit_button']);
  if (!is_numeric($_POST['platForm_Income_Reward']) || $_POST['platForm_Income_Reward'] > 100 || $_POST['platForm_Income_Reward'] < 0) 
  {
    echo '<script language="javascript">alert("请设置合理的网站所得比例");history.back();</script>'; exit();
  }

  
$Data = array('Shop_Commision_Reward_Json' => json_encode($_POST, JSON_UNESCAPED_UNICODE));


  $Flag=$DB->Set("shop_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
  if($Flag){
    echo '<script language="javascript">alert("修改成功");window.location="commision_setting.php";</script>';
  }else{
    echo '<script language="javascript">alert("保存失败");history.back();</script>';
  }
  exit;
} else {
	$shop_config = shop_config($_SESSION["Users_ID"]);
	$dis_config = dis_config($_SESSION["Users_ID"]);

  $Shop_Commision_Reward_Arr = array();
  if (!is_null($shop_config['Shop_Commision_Reward_Json'])) 
  {
    $Shop_Commision_Reward_Arr = json_decode($shop_config['Shop_Commision_Reward_Json'], true);
  }
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
			function Distribute(obj)
			{
				var curVal = $(obj).val();
				var topobj = $(obj).parent().parent().parent().find("input");
				var count = 0;
					topobj.each(function(){
						count += parseFloat($(this).val());
					});
					if(count>100){
						topobj.addClass("error");
						topobj.first().focus();
						alert("分销商比例不能大于100");
					}else{
						topobj.removeClass("error");
					}
			}
		</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
.dislevelcss{float:left;margin:5px 0px 0px 8px;text-align:center;border:solid 1px #858585;padding:5px;}
.dislevelcss th{border-bottom:dashed 1px #858585;font-size:16px;}
.error { color:#f00; border-color:#f00; }
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <script type='text/javascript'>
    	$(document).ready(shop_obj.products_edit_init);
    </script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="products.php">产品列表</a></li>
        <li class=""><a href="category.php">产品分类</a></li>
        <li class="cur"><a href="commision_setting.php">佣金设置</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form class="r_con_form" id="product_edit_form" method="post" action="?">

        <!--edit in 20160321-->

        <div class="rows">
          <label>网站发放比例</label>		  
          <span class="input price">
          <span>%</span>
          <input type="text" name="platForm_Income_Reward" value="<?php echo !empty($Shop_Commision_Reward_Arr['platForm_Income_Reward']) ? $Shop_Commision_Reward_Arr['platForm_Income_Reward'] : 0; ?>" class="form_input" size="5" maxlength="10" notnull />
          <span>(发放金额所占网站利润的百分比；小于100%大于0%；)</span>
          </span>
          <div class="clear"></div>
        </div> 
          
        <div class="rows">

          <label>佣金比例</label>		  
          <span class="input price">
          <span>%</span>
          <input type="text" name="commission_Reward" value="<?php echo !empty($Shop_Commision_Reward_Arr['commission_Reward']) ? $Shop_Commision_Reward_Arr['commission_Reward'] : 0; ?>" class="form_input" size="5" maxlength="10" notnull />
          <span>(佣金所占发放比例的百分比)</span>
          </span>
          <div class="clear"></div>
        </div> 
		
        <div class="rows">
        	<label>佣金返利<b class="red mousehand" id="allchange">（全部统一）</b></label>
            <span class="input">
			<?php
      $disidarr = [];
      $dislevelarrs = [];
			$dislevel = $DB->Get("distribute_level","Level_ID,Users_ID,Level_Name","where Users_ID='".$_SESSION["Users_ID"]."'");
			while($dislevelarr=$DB->fetch_assoc()){
			  $dislevelarrs[] = $dislevelarr;
			  $disidarr[] = $dislevelarr['Level_ID'];
		  }	

      $jsondisidarr = '';
      if (count($disidarr) > 0) {
  		  $jsondisidarr = json_encode($disidarr,JSON_UNESCAPED_UNICODE);
      }
		  $dislevelcont = count($dislevelarrs);	
			foreach($dislevelarrs as $key=>$disinfo){
			?>
			<div class="dislevelcss">
            	<table id="11" class="item_data_table" border="0" cellpadding="3" cellspacing="0">
				<tr><th><?=$disinfo['Level_Name']?></th></tr>
               		<?php 
						$arr = array('一','二','三','四','五','六','七','八','九','十');
						$level =  $dis_config['Dis_Self_Bonus']?$dis_config['Dis_Level']+1:$dis_config['Dis_Level'];						
						for($i=0;$i<$level;$i++){?>                        
						<tr>
							<td>
              &nbsp;&nbsp; %
								<input onblur="Distribute(this);" id="dischange<?=$disinfo['Level_ID'].$i?>" name="Distribute[<?=$disinfo['Level_ID']?>][<?php echo $i;?>]" value="<?php echo !empty($Shop_Commision_Reward_Arr['Distribute'][$disinfo['Level_ID']][$i]) ? $Shop_Commision_Reward_Arr['Distribute'][$disinfo['Level_ID']][$i] : 0; ?>" class="form_input" size="5" maxlength="10" type="text">
								(佣金比例的百分比)
							</td>
						</tr>
					<?php }?>
                </table>
				</div>
			<?php } ?>
            </span>
            <div class="clear"></div>
        </div>
       
        <div class="rows">

          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" /></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
/*edit in 20160321*/
var level = <?=$level?>;
var dislevelcont = <?=$dislevelcont?>;
var disidarr = <?=$jsondisidarr?>;
var fistarr = new Array();
$("#allchange").click(function(){
for(i=0;i<dislevelcont;i++){
	if(i == 0){
		for(j=0;j<level;j++){
		fistarr[j] = $("#dischange"+disidarr[i]+j).val();
		}	
	}else{
		for(j=0;j<level;j++){
		$("#dischange"+disidarr[i]+j).val(fistarr[j]);
		}
	}
}
})
</script>
</body>
</html>
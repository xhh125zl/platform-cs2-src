<?php require_once('top.php'); ?>
<?php
$head_img = '';
$head_name = '您还不是分销商<br />立即购买，马上加入我们';
$fx_enable = 0;
if(!empty($_SESSION[$UsersID."User_ID"])){
    $rsss = $DB->GetRs("shop_config","ShopName","where Users_ID='".$UsersID."'");
	$r = $DB->GetRs("user","User_HeadImg,User_NickName,Is_Distribute","where User_ID=".$_SESSION[$UsersID."User_ID"]);
	if($r){
		$head_img = $r["User_HeadImg"];
		if($r["Is_Distribute"]){
			$a = $DB->GetRs("shop_distribute_account","Enable_Tixian","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
			if($a){
				if($a["Enable_Tixian"]==1){
					$fx_enable = 1;
				}
			}
			$head_name = $r["User_NickName"].'<br />已为“'.$rsss["ShopName"].'”代言';
		}else{
			$head_name = $r["User_NickName"].'，您还不是分销商<br />立即购买，马上加入我们';
		}
	}
}
?>
<body>
<script type="text/javascript">
var myScroll;

function loaded () {
	myScroll = new IScroll("body", { mouseWheel:true});
}


</script>
	<link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
	<link href='/static/api/shop/skin/default/css/tao_detail.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
	<script src="/static/js/jquery.idTabs.min.js"></script>
    <script type='text/javascript' src='/static/api/shop/js/product_attr_helper.js'></script>

    <script language="javascript">
    var base_url = '<?=$base_url?>';
    var UsersID = '<?=$UsersID?>';
	var proimg_count = 1;
	var Products_ID = '<?=$rsProducts['Products_ID']?>';
	
    </script>
    <style type="text/css">
	#description1{ box-sizing:border-box; background:#FFF; padding:0px}
	#description1 .contents{line-height:180%; overflow:hidden; min-height:250px;}
	#description1 .contents img{max-width:100%}
	#footer{width:100%; height:50px; line-height:50px; background:#333333; padding:0px; margin:0px;}
	#footer #footer-panel{height:55px; width:100%; padding:0px; margin:0px}
	#footer #footer-panel #button-conteiner{width:100%}
	#diymenu{width:100px; height:auto; background:url('/static/api/shop/skin/default/images/diymenu.png') no-repeat 60px bottom; position:fixed; bottom:120px; right:8px; padding:6px 6px 6px 0px; display:none}
	#diymenu p{margin:0px; padding:0px; width:20px; height:20px; background:rgba(0,0,0,0.7); border-radius:50%; position:absolute; top:0px; right:0px;}
	#diymenu p b{color:#FFF; font-size:20px; font-weight:normal; display:block; width:100%; height:100%; line-height:16px; text-align:center}
	#diymenu #menulist{width:100%; background:rgba(0,0,0,0.7); padding:10px 0px; border-radius:5px;}
	#diymenu #menulist span{display:block; width:100%; height:30px; line-height:30px; text-align:center; color:#FFF; font-size:14px;} 
	#diymenu #menulist span a{color:#FFF; font-size:14px}
	
	#diybtn{width:30px; position:fixed; bottom:120px; right:8px; background:rgba(0,0,0,0.7); border-radius:5px; text-align:center; padding-bottom:5px}
	#diybtn span{display:block; color:#FFF; font-size:12px;}
	</style>
    <div id="overlay"></div>
    <div class="wrap"> 
        <div id="description1">
          <div class="contents"><?=$rsProducts["Products_Description"]?></div>
        </div>
     </div>
    <!-- 属性选择内容begin -->
   
    <div id="option_content">
        <div id="option_selecter">
			<form name="addtocart_form" action="/api/<?php echo $UsersID ?>/shop/cart/" method="post" id="addtocart_form">
            <input type="hidden" name="OwnerID" value="<?=$owner['id']?>"/>
            <?php if( $rsProducts["Products_IsShippingFree"] == 1):?>
            <input type="hidden" name="IsShippingFree" value="1"/>
            <?php else:?> 
            <input type="hidden" name="IsShippingFree" value="0"/>
            <?php endif;?>
            <input type="hidden" name="ProductsWeight" value="<?=$rsProducts["Products_Weight"];?>"/>
    
	  		<div id="simple-info">
                <div id="product-thumb">
                    <img width="80px" height="80px" src="<?=$rsProducts['ImgPath']?>" />
                </div>
                <div id="info-txt" style="padding:0px; margin:0px">
                    <div class="orange" style="padding:0px; margin:0px; height:40px; line-height:50px;">&yen;<span id="cur-price-txt"><?=$cur_price?></span></div>
                    <div  style="padding:0px; margin:0px; height:40px; line-height:50px;">库存<span id="stock_val"><?=$rsProducts['Products_Count']?></span>件</div>
                   <div class="clearfix"></div>
                </div>
                <div id="close-btn"><a href="javascript:void(0)"><span class="fa fa-remove"></span></a></div>
                <div class="clearfix"></div>
      		</div>
            
      		<div class="option-val-list">
        		<!-- choose begin -->       		
                <ul id="choose" class="list-group">
                <!-- {* 开始循环所有可选属性 *} -->
                <!-- {foreach from=$specification item=spec key=spec_key} -->
		 	 	<?php $spec_list = array(); ?>
          		<?php foreach($specification as $spec_key=>$spec):?>
          			<li  id="choose-version" class="list-group-item">
            			<div class="dt"><?=$spec['Name']?>：</div>
            			<div class="dd catt">
              			<!-- {* 判断属性是复选还是单选 *} -->
             
			  			<?php if($spec['Attr_Type'] == 1):?>		
              		
             		<?php foreach($spec['Values'] as $key=>$value):?>
             	<a  class="<?=($key == 0)?'cattsel':'';?>"  onclick="changeAtt(this)" href="javascript:;" name="<?=$value['id']?>" title="<?=$value['label']?>">					<?php if($key == 0){
						$spec_list[] = $value['id'];
						
					};
					?>
             	   	<?=$value['label']?>
        	    <input style="display:none" id="spec_value_<?=$value['id']?>" type="radio" name="spec_<?=$spec_key?>" value="<?=$value['id']?>" <?=($key == 0)?'checked':'';?> /></a>
              		
              		<?php endforeach; ?>
              	
              		
              
            	<?php else: ?>
            
              <?php foreach($spec['Values'] as $key=>$value):?>
              	
	    
              <label for="spec_value_<?=$value['id']?>">
              <?=$value['label']?>
                <input type="checkbox" name="spec_<?=$spec_key?>[]" value="<?=$value['id']?>" id="spec_value_<?=$value['id']?>" onClick="changePrice()" />
               
                </label>
                
         
              
              <!-- {/foreach} -->
              <?php endforeach; ?>
               <div class="clearfix"></div>
           
               <?php endif; ?>
            			</div>
            			<div class="clearfix"></div>
          			</li>
          		<?php endforeach; ?>
         		</ul>
          
		     <input type="hidden" id="spec_list" name="spec_list" value="<?=implode(',',$spec_list)?>" />
          <!-- {* 结束循环可选属性 *} -->
        <!--choose end-->	
          
          <div id="qty_selector">
          <input type="hidden" id="cur_price" value="<?=$cur_price?>"/>
          <input type="hidden" id="no_attr_price" value="<?=$no_attr_price?>"/>
           	  数量:
         <a href="javascript:void(0)" class="qty_btn" name="minus">-</a><input type="text" name="Qty" maxlength="3" style="height:28px; line-height:28px" value="1" pattern="[0-9]*" /><a href="javascript:void(0)" class="qty_btn" name="add">+</a>
          </div>    
           
            </div>

      	<input type="hidden" name="ProductsID" value="<?php echo $ProductsID ?>" />
		<input type="hidden" id="needcart" name="needcart" value="1" />
     </form>
            <a href="#" id="selector_confirm_btn">确定</a>
        </div>
     </div>
        <!-- 属性选择内容end -->
        <!-- 页脚panle begin -->
        <div id="footer_panel_content">
            <ul id="footer-panel">
                <li class="button-conteiner" style="width:100%; padding-left:60px; line-height:20px; color:#FFF; box-sizing:border-box; font-size:12px;">
					<?php if($fx_enable==1){?>
					<a href="<?php echo $shop_url.'distribute/'?>" style="display:block; float:right; width:80px; height:34px; line-height:32px; text-align:center; color:#FFF; font-size:12px; background:#00a0e9; margin:8px 8px 0px 0px; padding:0px; border-radius:8px">分销中心</a> 
					<?php }else{?>
					<a href="javascript:void(0)" id="menu-direct-btn" style="display:block; float:right; width:80px; height:34px; line-height:32px; text-align:center; color:#FFF; font-size:12px; background:#00a0e9; margin:8px 8px 0px 0px; padding:0px; border-radius:8px">立即购买</a>
					<?php }?>
					<?php if($head_img){?><img src="<?php echo $head_img;?>" style="display:block; width:45px; height:45px; position:absolute; top:2px; left:2px" /><?php }?>
					<p style="font-size:12px; color:#FFF; height:36px; overflow:hidden; line-height:17px; margin:0px; padding:5px 0px 0px 0px"><?php echo $head_name;?></p>
				</li>
                <div class="clearfix"></div>
            </ul>
        </div>
        <!-- 页脚panle end -->
    <footer id="footer"></footer>
    
    <script language='javascript'>
    var KfIco = '/static/kf/ico/00.png';
    var OpenId = '';
    var UsersID = '<?=$UsersID?>';
	var is_virtual = <?php echo $rsProducts["Products_IsVirtual"]?>;
	$(document).ready(function() {
		
        shop_obj.products_buy_init();
        $("#content-filter").idTabs();
		$("#diybtn").click(function(){
			$(this).slideUp();
			$("#diymenu").slideDown();
		});
		$("#menuclose").click(function(){
			$("#diymenu").slideUp();
			$("#diybtn").slideDown();
		});
    });
    </script>
   
<?php
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsShop=1 and KF_Code<>''");
if($kfConfig){
	echo htmlspecialchars_decode($kfConfig["KF_Code"],ENT_QUOTES);
}
?>
 
<?php if($rsConfig["CallEnable"] && $rsConfig["CallPhoneNumber"]){?>
<script language='javascript'>var shop_tel='<?php echo $rsConfig["CallPhoneNumber"];?>';</script>
<script type='text/javascript' src='/static/api/shop/js/tel.js?t=<?php echo time();?>'></script>
<?php }?>

<?php if(!empty($share_config)){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_config["appId"];?>",   
		   timestamp:<?php echo $share_config["timestamp"];?>,
		   nonceStr:"<?php echo $share_config["noncestr"];?>",
		   url:"<?php echo $share_config["url"];?>",
		   signature:"<?php echo $share_config["signature"];?>",
		   title:"<?php echo $share_config["title"];?>",
		   desc:"<?php echo str_replace(array("\r\n", "\r", "\n"), "", $share_config["desc"]);?>",
		   img_url:"<?php echo $share_config["img"];?>",
		   link:"<?php echo $share_config["link"];?>"
		};
		
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>

    <div class='conver_favourite'><img src="/static/api/images/global/share/favourite.png" /></div>
    <span style="display:none" id="buy-type"></span>
    <?php
	 if(!empty($rsConfig["DiyMenu"])){
		 $MenuList = $rsConfig["DiyMenu"] ? json_decode($rsConfig['DiyMenu'],true) : array();
	?>
    <div id="diybtn">
     <span style="font-size:8px;">●●●</span>
     <span>展<br />开<br />导<br />航</span>
    </div>
    <div id="diymenu">
     <p id="menuclose"><b>×</b></p>
     <div id="menulist">
    <?php
		 foreach($MenuList as $m){
			 if($m["link"]){
				 echo '<span><a href="'.$m["link"].'">'.$m["name"].'</a></span>';
			 }else{
				 echo '<span>'.$m["name"].'</span>';
			 }
	?>
     
    <?php }?>
     </div> 
    </div>
    <?php }?>
	<?php require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/substribe.php');?>
</body>

</html>

<?php require_once('top.php'); ?>
<body >
<script type='text/javascript' src='/static/js/iscroll.js'></script> 
<link href="/static/css/bootstrap.css" rel="stylesheet" />
<link rel="stylesheet" href="/static/css/font-awesome.css" />
<link href="/static/api/cloud/css/products.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<script src="/static/js/jquery.idTabs.min.js"></script> 
<!-- 产品图片所需插件 begin -->
<link href="/static/js/plugin/photoswipe/photoswipe.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/static/js/plugin/touchslider/touchslider.min.js"></script> 
<script type='text/javascript' src='/static/js/plugin/photoswipe/klass.min.js'></script> 
<script type='text/javascript' src='/static/js/plugin/photoswipe/photoswipe.jquery-3.0.5.min.js'></script> 
<script type='text/javascript' src='/static/api/shop/js/toast.js'></script> 
<script type='text/javascript' src='/static/api/cloud/js/countdown.js'></script> 
<style type="text/css">
    .guangguang {    width: 80px;  height: 30px; line-height: 30px; background: #eb2c00; padding-left: 20px; color: #fff; position: absolute; z-index: 999;  right: 5px;}
    .guangguang a {display:block;color:#fff;}
    .buy_bj1,buy_bj a{display:block;}
</style>
<!-- 产品图片所需插件 end --> 

<script language="javascript">
	var base_url = '<?=$base_url?>';
    var Products_ID = '<?=$rsProducts['Products_ID']?>';
	var proimg_count = 1;
	var is_virtual = <?php echo $rsProducts["Products_IsVirtual"]?>;
	$(document).ready(function() {
		shop_obj.tao_detail_init();
		$("#content-filter").idTabs();
	});
</script>
<header class="bar bar-nav"> <a href="javascript:history.go(-1)" class="pull-left"><img src="/static/api/shop/skin/default/images/black_arrow_left.png" /></a>
    <h1 class="title" id="page_title">产品详情</h1>
</header>
<div id="overlay"></div>
<div id="wrap"> 
  <!-- 产品图片开始 -->
  <div id="detail_images">
    <div class="pro_img">
      <div class="touchslider">
        <div class="img">
          <div class="touchslider-viewport">
            <div class="list">
            <?php if(isset($JSON["ImgPath"])){
				foreach($JSON["ImgPath"] as $key=>$value){
					echo '<div class="touchslider-item"><a href="'.$value.'" rel="'.$value.'"><img src="'.$value.'" /></a></div>';
				}
			}?>
            </div>
          </div>
        </div>
        <div class="touchslider-nav">
          <?php
			if(isset($JSON["ImgPath"])){
				foreach($JSON["ImgPath"] as $key=>$value){
					echo $key==0 ? '<a class="touchslider-nav-item touchslider-nav-item-current"></a>' : '<a class="touchslider-nav-item"></a>';
				}
			}?>
        </div>
      </div>
    </div>
  </div>
  <!-- 产品图片结束 -->
  <?php if($rsProducts['zongrenci']-$rsProducts['canyurenshu'] > 0){?>
    <div class="conbox">
		<div id="product_brief_info">
		  <?php if($isOpenShop){ ?>
		  <div class="guangguang"><a href="/api/<?=$UsersID ?>/cloud/biz/<?=$biz ?>/act_<?=$_SESSION[$UsersID.'_CurrentActive'] ?>/">逛逛店铺</a></div>
		  <?php } ?>
		  <div id="name_and_share"> <span id="product_name" style="padding:0;">
			(第<?php echo $rsProducts["qishu"];?>期)&nbsp;<?=$rsProducts["Products_Name"]?>
			</span> <span id="shu_xian" style="display:none;"></span> <a class="detail_share" id="share_product" productid="<?=$ProductsID?>" is_distribute="<?=$Is_Distribute?>" style="display:none;">分销此商品</a>
			<div class="clearfix"></div>
			<span id="product_price">&yen;
			<?=$rsProducts["Products_PriceX"]?>
			元</span>&nbsp;&nbsp;&nbsp;&nbsp; <del id="product_origin_price">&yen;
			<?=$rsProducts["Products_PriceY"]?>
			元</del> </div>
			
			<div class="gRate">
				<div class="Progress-bar"><p title="已完成<?php echo $rsProducts['canyurenshu']/$rsProducts['zongrenci']*100;?>%" class="u-progress"><span style="width:<?php echo $rsProducts['canyurenshu']/$rsProducts['zongrenci']*100;?>%;" class="pgbar"><span class="pging"></span></span></p><ul class="Pro-bar-li"><li class="P-bar01"><em><?php echo $rsProducts['canyurenshu'];?></em>已参与</li><li class="P-bar02"><em><?php echo $rsProducts['zongrenci'];?></em>总需人次</li><li class="P-bar03"><em><?php echo $rsProducts['zongrenci']-$rsProducts['canyurenshu'];?></em>剩余</li></ul></div>
			</div>
		</div>
    </div>
    <?php }else {?>
	<div class="Countdown-con" id="fnTimeCountDown">
        <div class="g-Countdown">
            <p class="orange">已满员，揭晓结果即将公布</p>
            <div>
                <cite>
                    <span class="mini">00</span>
                    <em>:</em>
                    <span class="sec">00</span>
                    <em>:</em>
                    <span class="hm">000</span>
                </cite>
            </div>
        </div>
    </div>
	<script>
		var going = true;
		<?php $yy = $DB->GetRs('cloud_record','Add_Time','where Products_ID='.$rsProducts['Products_ID'].' order by Record_ID desc');?>
		$('#fnTimeCountDown').fnTimeCountDown('<?php echo date('Y/m/d H:i:s', $yy['Add_Time']+3 * 60)?>');
		function djs_callback(){
			$('#fnTimeCountDown .hm').html('000');
			$.ajax({
				type:'post',
				url:'/api/'+UsersID+'/cloud/ajax/',
				data:{action:'lottery',ProductsID:'<?php echo $rsProducts['Products_ID'];?>'},
				beforeSend:function() {
					$('#fnTimeCountDown .orange').html('正在计算中...结果马上揭晓！');
				},
				success:function(data) {
					if(data.status == 1) {
						location.href = '/api/'+UsersID+'/cloud/lottery/'+data.detail_id+'/';
					}
				},
				complete:function() {
					
				},
				dataType:'json',
			});
        }
	</script>
	<?php }?>
</div>

<!-- 会员专享价begin -->
<?php if(count($discount_list) > 0):?>
<div class="b5"></div>
<div class="detail_panel_title">
    <div class="p_info"><a href="javascript:void(0)">会员专享价</a></div>
</div>
<div class="b5"></div>
<div class="detail_panel_content">
  <ul class="list-group" id="user_level_price">
    <?php foreach($discount_list as $key=>$item):?>
    <?php if($item['cur'] == 1):?>
    <li class="list-group-item red">&nbsp;&nbsp;
      <?=$item['Name']?>
      价&nbsp;&nbsp;<strong>&yen;
      <?=$item['price']?>
      </strong></li>
    <?php else: ?>
    <li class="list-group-item">&nbsp;&nbsp;
      <?=$item['Name']?>
      价&nbsp;&nbsp;<strong class="red">&yen;
      <?=$item['price']?>
      </strong></li>
    <?php endif;?>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>
<!-- 会员专享价end -->

<div id="detail">
  <div class="b5"></div>
  <?php if(!$rsCommit['num'] == 0){?>
  <div class="commit"><a href="<?=$base_url?>api/<?=$UsersID?>/cloud/commit/<?=$ProductsID?>/"><span>&nbsp;</span>商品评价
    <label class="juice">(共
      <?=$rsCommit['num']?>
      条)</label>
    <label class="pull-right juice">
      <?=$rsCommit['points']?>
      分</label>
    </a></div>
  <div class="b5"></div>
  <?php }?>
</div>
<div class="commit">
<a href="<?php echo $cloud_url.'active_buyrecords/'.$rsProducts['Products_ID'].'/';?>"><span> </span><label>所有参与记录</label></a>
</div>
<div class="b5"></div>
<div class="commit">
<a href="<?php echo $cloud_url.'category/0/'?>"><span> </span><label>所有购物活动</label></a>
</div>
<div class="b5"></div>
<div class="commit">
<a href="<?php echo $cloud_url.'active_buyrecords/'.$rsProducts['Products_ID'].'/myself/1/';?>"><span> </span><label>我的购买记录</label></a>
</div>
<div class="b5"></div>
<div class="commit">
<a href="<?php echo $cloud_url.'Morelottery/'.$rsProducts['Products_ID'].'/';?>"><span> </span><label>本商品往期记录</label></a>
</div>
<div class="b5"></div>
<!-- 产品详情start -->
<div id="content-filter" class="center-block">
  <div style="width:100%;line-height:40px;height:40px;font-size:18px;overflow:hidden;border-top:solid 1px #dcdcdc;border-bottom:solid 1px #eeeeee">
	产品详情
  </div>
  <div id="table-panel">
    <div id="description">
      <div class="contents">
        <?=$rsProducts["Products_Description"]?>
      </div>
    </div>
  </div>
  <a href="#" class="clearfix"></a> 
</div>
 <!-- 产品详情end -->
<!-- 页脚panle begin -->
<div id="footer_panel_content">
	<ul id="footer-panel">
		<li class="icon-container">
			<span>
				<?php
					$id = $rsProducts['Products_IsFavourite']?'favorited':'favorite';
				?>
				<b id="<?=$id?>"  isFavourite="<?=$rsProducts['Products_IsFavourite']?>" productid="<?=$rsProducts['Products_ID']?>">收藏</b> 
			</span>
			<span><b class="cart" onClick="javascript:location.href='/api/<?=$UsersID?>/cloud/cart/';">购物车</b>
			
			<?php 
			    $car_num = 0;
				if(!empty($_SESSION[$UsersID."CloudCart"]) && $_SESSION[$UsersID."CloudCart"] != 'null'){
					$sessionCart = json_decode($_SESSION[$UsersID."CloudCart"],true);
					foreach($sessionCart as $k_ProductsID => $v){
						$car_num += $v[0]['Qty'];
					}
				}
			?>
			<i <?php if(empty($car_num)){?>style="display:none"<?php }?>><?php echo $car_num;?></i>
			</span>
		</li>
		<?php if($rsProducts['Products_IsVirtual']==0){?>
		<li class="button-conteiner"><a href="javascript:void(0)" id="menu-direct-btn">立即购买</a></li>
		<li class="button-conteiner"><a href="javascript:void(0)" id="menu-addtocard-btn">加入购物车</a></li>
		<?php }else{?>
		<li class="button-conteiner-virtual" style="width:160px;float:right;margin-right:10px;"><a href="javascript:void(0)" id="menu-direct-btn">立即购买</a></li>
		<?php }?>
		<div class="clearfix"></div>
	</ul>
</div>
<!-- 页脚panle end --> 
<footer id="footer"></footer>
<div id="back-to-top"></div>
<script language='javascript'>
    var KfIco = '/static/kf/ico/00.png';
    var OpenId = '';
    var UsersID = '<?=$UsersID?>';
</script>
<?php if(!empty($kfConfig)){?>
<script language='javascript'>var KfIco='<?php echo $KfIco;?>'; var OpenId='<?php echo empty($_SESSION[$UsersID."OpenID"]) ? '' : $_SESSION[$UsersID."OpenID"];?>'; var UsersID='<?php echo $UsersID;?>'; </script> 
<script type='text/javascript' src='/kf/js/webchat.js?t=<?php echo time();?>'></script>
<?php }?>
<?php if($rsConfig["CallEnable"] && $rsConfig["CallPhoneNumber"]){?>
<script language='javascript'>var shop_tel='<?php echo $rsConfig["CallPhoneNumber"];?>';</script> 
<script type='text/javascript' src='/static/api/shop/js/tel.js?t=<?php echo time();?>'></script>
<?php }?>
<?php if($share_flag==1 && $signature<>""){?>
<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_user["Users_WechatAppId"];?>",		   
		   timestamp:<?php echo $timestamp;?>,
		   nonceStr:"<?php echo $noncestr?>",
		   url:"<?php echo $url?>",
		   signature:"<?php echo $signature;?>",
		   title:"<?php echo empty($share_title) ? $rsConfig["ShopName"] : $share_title;?>",
		   desc:"<?php echo empty($share_desc) ? $rsConfig["ShopName"] : str_replace(array("\r\n", "\r", "\n"), "", $share_desc);?>",
		   img_url:"<?php echo empty($share_img) ? $rsConfig["ShopLogo"] : $share_img;?>",
		   link:"<?php echo empty($share_id) ? (empty($share_link) ? '' : $share_link) : 'http://'.$_SERVER['HTTP_HOST'].'/api/'.$UsersID.'/'.(empty($owner["id"]) ? '' : $owner["id"].'/').'share_recieve/'.$share_id.'/'; ?>"
		};
		
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>
	<div class='conver_favourite'><img src="/static/api/images/global/share/favourite.png" /></div>
	<span style="display:none" id="buy-type"></span>
	<?php require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/substribe.php');?>
	<script>  
	    $(function(){  
			//当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失  
			$(window).scroll(function(){  
				if ($(window).scrollTop()>100){  
					$("#back-to-top").fadeIn(1500);  
				}  
				else  
				{  
					$("#back-to-top").fadeOut(1500);  
				}  
			});  
	  
			//当点击跳转链接后，回到页面顶部位置  
	  
			$("#back-to-top").click(function(){  
				$('body,html').animate({scrollTop:0},1000);  
				return false;  
			}); 
			if(typeof(going) != 'undefined' && going){
				$('#footer').html('<ul id="footer-panel"><li class="button-conteiner" style="width:90%;margin:0 auto;float:none;"><a href="<?php echo $cloud_url.'products/'.$rsProducts['Products_ID'].'/';?>" style="background:#eb2c00;color: #ffffff;display: block;font-size: 16px;height: 35px;line-height: 35px;text-align: center;">第<?php echo $rsProducts['qishu']+1;?>云正在进行中...</a></li></ul>').hide();
				$('.commit a').click(function (event) {
					$(this).css("color","#CCC").unbind("click");
					$(this).attr('href', '#fnTimeCountDown');
	                event.preventDefault();
	            });
			}		
		});  
	</script>
	<form name="addtocart_form" action="/api/<?php echo $UsersID ?>/cloud/cart/" method="post" id="addtocart_form" style="display:none;">
        <input type="hidden" name="OwnerID" value="<?=$owner['id']?>"/>
		<?php if( $rsProducts["Products_IsShippingFree"] == 1){?>
		<input type="hidden" name="IsShippingFree" value="1"/>
		<?php }else{?>
		<input type="hidden" name="IsShippingFree" value="0"/>
		<?php }?>
        <input type="hidden" name="ProductsWeight" value="<?=$rsProducts["Products_Weight"];?>"/>
		<input type="hidden" name="Qty" value="1" />
	    <input type="hidden" name="ProductsID" value="<?php echo $ProductsID ?>" />
		<input type="hidden" id="needcart" name="needcart" value="1" />
    </form>
</body>
</html>
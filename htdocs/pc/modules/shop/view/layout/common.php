<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/jquery-1.7.2.min.js"></script>
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/jquery.SuperSlide.js"></script>
<script src="<?php echo $output['_site_url'];?>/static/js/plugin/layer/layer.js"></script> 

<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/public.css" rel="stylesheet" type="text/css" />
<div class="top_bg">
	<div class="top">
		<div class="top-right">
			<ul>
				<li><a href="<?php echo url('member/index');?>">会员中心</a><i><img src="<?php echo $output['_site_url'];?>/static/pc/shop/images/_.png" /></i>
					<div id="sevice">
						<ul>
							<li><a href="<?php echo url('member/address');?>">收货地址</a></li>
							<li><a href="<?php echo url('member/status', array('Status'=>0));?>">我的订单</a></li>
							<li><a href="<?php echo url('member/backup')?>">退款单</a></li>
							<li><a href="<?php echo url('member/shoucang')?>">我的收藏</a></li>
							<li><a href="<?php echo url('member/money')?>">我的余额</a></li>
							<li><a href="<?php echo url('member/personal_information');?>">个人信息</a></li>
						</ul>
					</div>
				</li>
				<li id="specer"></li>
				<li><a href="javascript:;">分销中心</a><i><img src="<?php echo $output['_site_url'];?>/static/pc/shop/images/_.png" /></i>
					<div id="sevice">
						<ul>
							<li><a href="<?php echo url('distribute/distribute_record');?>">分销记录</a></li>
							<li><a href="<?php echo url('distribute/distribute_withdraw');?>">佣金提现</a></li>
							<li><a href="<?php echo url('distribute/distribute_withdraw_method');?>">提现方式</a></li>

<!--							<li><a href="<?php //echo url('distribute/distribute_qrcodehb');?>" target="_blank">二维码</a></li>-->
<!--							<li><a href="<?php //echo url('distribute/pro_title');?>">我的爵位</a></li>-->

						</ul>
					</div>
				</li>
				<li id="specer"></li>
			</ul>
		</div>
		<?php if(empty($_SESSION[$output['UsersID'] . 'User_ID'])) {?>

		<div class="top_left"><em>您好，欢迎来到<a href="<?php echo url('index/index');?>"><?php echo $output['shopConfig']['shopname'];?></a>网上商城</em><a href="<?php echo url('public/login');?>" class="login">请登录</a><a  href='javascript:void(0)' id='reg' url="<?php echo url('public/register',array('UsersID'=>$output['UsersID']));?>" class="sign">免费注册</a>&nbsp;&nbsp;<!--<a href="<?php //echo url('distribute/distribute_invite');?>" style="color:#d30015;">邀请返利</a>--></div>

	    <?php }else {?>
		<div class="top_left"><em>您好，<a href="<?php echo url('member/index');?>"><?php echo $_SESSION[$output['UsersID'] . 'User_Name'];?></a>欢迎来到&nbsp;<a href="<?php echo url('index/index');?>"><?php echo $output['shopConfig']['shopname'];?></a>&nbsp;网上商城</em><a href="<?php echo url('public/logout',array('UsersID'=>$output['UsersID']));?>" class="sign">[退出]</a>&nbsp;&nbsp;<!--<a href="<?php //echo url('distribute/distribute_invite');?>" style="color:#d30015;">邀请返利</a>--></div>
		<?php }?>
	</div>
</div>
<div class="w_bg">
	<div class="w">
		<div class="logo"><img src="<?php echo $output['shopConfig']['logo'];?>" /></div>
		<form action="<?php echo url('list/index');?>" method="get">
			<div class="search">
				<div class="form">
					<input type="text" class="text" placeholder="请输入你要搜索的商品名称" name="Keyword" value="<?php echo empty($_GET['Keyword']) ? '' : $_GET['Keyword'];?>"/>
					<button class="button" type="submit">提交</button>
				</div>
			</div>
		</form>
		<div class="settleup">
			<div class="cw_icon"> <i class="ci-left ci_shopcar"><?php echo !empty($output['car_num']) ? '<b class="hize">' . $output['car_num'] . '</b>' : '<b></b>';?></i> <a href="<?php echo url('buy/cart');?>">购物车结算</a> </div>
		</div>
	</div>
</div>

<?php require_once($output['tpl_file']);?>

<div class="foot_border">
	<div class="foot" style="overflow:hidden;">
		<?php if($output['articles'][0]){?>
		<?php foreach($output['articles'][0] as $key => $val){?>
		<dl class="<?php if($key == 0){?>fore1<?php }else{?>fore2<?php }?>" style="float:left;width:240px;margin:0;">
			<dt><?php echo $val['Category_Name'];?></dt>
			<?php foreach($output['articles'][1] as $k => $v){?>
			<?php if($val['Category_ID'] == $v['Category_ID']){?>
			<dd><a href="<?php echo url('article/index', array('id'=>$v['Article_ID']));?>"><?php echo $v['Article_Title']?></a></dd>
			<?php }?>
			<?php }?>
		</dl>
		<?php }?>
		<?php }?>
	</div>
</div>
<div class="end_bg">
	<div class="end">
		<div>
			<p>Copyright 2016 </p>
		</div>
	</div>
</div>
<!-- 头部导航小功能begin-->
<script>
	$(document).ready(function(e) {
        $('.top-right li').hover(function(){
				$( this).children('div#sevice').show().parent().css({'background':'#FFFFFF','outline':'1px solid #dcdcdc'});
			},function(){
				$( this).children('div#sevice').hide().parent().css({'background':'','outline':''});
			}
		)
    });

    $("#reg").click(function(){
            var url = $("#reg").attr('url');
            layer.open({
                title:'注册',
                type: 2,
                area:['1000px','600px'],
                content: url
            });
            
        })

</script>
<!-- 头部导航小功能end-->
<?php if($output['_controller'] != 'index') {?>
<script>
$(document).ready(function(e) {
    $('.pullDownList').hide();
	$('.pullDown').hover(function() {
		$('.pullDownList').show();
	},function(){
		$('.pullDownList').hide();
	})
});
</script>
<?php }else {?>
<script type="text/javascript">
    function trim(str){ 
　　    return str.replace(/(^\/*)|(\/*$)/g, '');
　　}
    var ownerid = <?php echo $output['ownerid'];?>;
    $('.public a,.main_nav_bg a,.mb_mune a,.mb_brand a,.luara-left a').each(function(){
		if(ownerid){
			var this_href = trim($(this).attr('href'));
			if(this_href.indexOf('goods/index') != -1 || this_href.indexOf('list/index') != -1){//站内url才处理
				if(this_href.indexOf('http://') != -1){
					var final_href = this_href+'/OwnerID/'+ownerid;
				}else{
					var final_href = '<?php echo $output['_site_url'];?>/'+this_href+'/OwnerID/'+ownerid;
				}
				$(this).attr('href',final_href);
			}
		}
	});
</script>
<?php }?>
<script>
	  $(function(){
		// 导航左侧栏js效果 start
		$('.pullDownList li').hover(function(){
		    var showID = '#'+$(this).attr('rel');
			if($(showID).length){
			    $('.yMenuListCon').hide();
			    $(showID).show();
			}else{
			    $('.yMenuListCon').hide();
			}
			var index=$(this).index('.pullDownList li');
			if (!($(this).hasClass('menulihover')||$(this).hasClass('menuliselected'))) {
				$($('.yBannerList')[index]).css('display','block').siblings().css('display','none');
				$($('.yBannerList')[index]).removeClass('ybannerExposure');
				setTimeout(function(){
				$($('.yBannerList')[index]).addClass('ybannerExposure');
				},60)
			}else{	
			}
			$(this).addClass('menulihover').siblings().removeClass('menulihover');
				$(this).addClass('menuliselected').siblings().removeClass('menuliselected');
			$($('.yMenuListConin')[index]).show().siblings().hide();
		},function(){
			
		})
		$('.pullDown').mouseleave(function(){
			$('.yMenuListCon').hide();
			$('.yMenuListConin').hide();
			$('.pullDownList li').removeClass('menulihover');
		})
		// 导航左侧栏js效果  end
	});

</script>
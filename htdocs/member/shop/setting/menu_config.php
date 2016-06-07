<?php
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$front_menu = array('首页', '分销中心', '购物车', '个人中心');

$DefaultMenu = array(
	'menu' => array(
		array('menu_name' => '首页', 'login_menu_name' => '', 'icon' => '/static/api/distribute/images/home.png', 'menu_href' => '/api/' . $_SESSION['Users_ID'] . '/shop/', 'login_menu_href' => '/api/' . $_SESSION['Users_ID'] . '/shop/', 'bind_action_attr' => 0, 'menu_order' => '1'),
		array('menu_name' => '我要分销', 'login_menu_name' => '分销中心', 'icon' => '/static/api/distribute/images/sitemap.png', 'menu_href' => '/api/' . $_SESSION['Users_ID'] . '/distribute/join/', 'login_menu_href' => '/api/' . $_SESSION['Users_ID'] . '/distribute/', 'bind_action_attr' => 1, 'menu_order' => '2'),
		array('menu_name' => '购物车', 'login_menu_name' => '', 'icon' => '/static/api/distribute/images/cart.png', 'menu_href' => '/api/' . $_SESSION['Users_ID'] . '/shop/cart/', 'login_menu_href' => '/api/' . $_SESSION['Users_ID'] . '/shop/cart/', 'bind_action_attr' => 2, 'menu_order' => '3'),
		array('menu_name' => '个人中心', 'login_menu_name' => '', 'icon' => '/static/api/distribute/images/user.png', 'menu_href' => '/api/' . $_SESSION['Users_ID'] . '/shop/member/', 'login_menu_href' => '/api/' . $_SESSION['Users_ID'] . '/shop/member/', 'bind_action_attr' => 0, 'menu_order' => '4'),
	)
);


if (!empty($_POST)) 
{
	if ($_POST['method'] = 'ajaxpost' && !empty($_POST['menuId'])) 
	{
		$rsMenuConfig = $DB->GetRs('shop_config', 'ShopMenuJson', ' WHERE  Users_ID="' .$_SESSION['Users_ID']. '"');
		$ShopMenu = empty(json_decode($rsMenuConfig['ShopMenuJson'], TRUE)) ? $DefaultMenu : json_decode($rsMenuConfig['ShopMenuJson'], TRUE);

		unset($ShopMenu['menu'][$_POST['menuId']]);

		if (!empty($ShopMenu)) {
			$Data=array(
				"ShopMenuJson"=>json_encode($ShopMenu,JSON_UNESCAPED_UNICODE),
			);
			$Flag=$DB->Set("shop_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
			if ($Flag) {
				echo json_encode(array('status' => 1, 'msg' => '删除成功！'));
			} else {
				echo json_encode(array('status' => 0, 'msg' => '删除失败！'));
			}
		}
		exit();
	}

	foreach ($_POST['menu'] as $k => &$v) {
		if (empty(trim($v['menu_name'])) || empty(trim($v['menu_href']))) {
			unset($_POST['menu'][$k]);
		}
	}
	$Data=array(
		"ShopMenuJson"=>json_encode($_POST,JSON_UNESCAPED_UNICODE),
	);
	$Flag=$DB->Set("shop_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	if($Flag){
		echo '<script language="javascript">alert("添加成功");window.location="menu_config.php";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
} else {
	$rsMenuConfig = $DB->GetRs('shop_config', 'ShopMenuJson', ' WHERE  Users_ID="' .$_SESSION['Users_ID']. '"');

	$ShopMenu = empty(json_decode($rsMenuConfig['ShopMenuJson'], TRUE)) ? $DefaultMenu : json_decode($rsMenuConfig['ShopMenuJson'], TRUE);

}

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js?t=<?php echo time() ?>'></script>
<style type="text/css">
	div.m_righter { width: 800px; display: block; background: #eee; border: 1px solid #ccc; padding: 10px 20px 20px; clear: both; }
	div.m_righter h1 { display: block; overflow: hidden; height: 30px; line-height: 30px; font-size: 14px; font-weight: bold; }
	.r_con_form .rows .input .form_input { width: 200px; }
	.r_con_form .rows .input .long_form_input { width: 400px; }
	.r_con_form .no-border, .r_con_form .rows .no-border { border: 0; }
	.menu_list { display: block; overflow: hidden; margin-bottom: -1px; }
	.menu_list a  { display: inline-block; float: left; padding: 1px 5px; background: #eee; color: #000; width: 112px; text-align: center; font-size: 16px; border: 1px solid #ccc; margin-right: -1px; height: 50px; line-height: 50px; margin-top: 20px; margin-right: 20px; margin-bottom: 21px; position: relative; }
    .menu_list a.current { background: #1584D5; color: #fff; }
    .menu_list a.current span.arrow { width: 0px; height: 0px; border-left: 15px solid transparent; border-right: 15px solid transparent; border-bottom: 22px solid #ccc; font-size: 0px; line-height: 0px; position: absolute; top: 52px; left: 40px; }

    .menu_list a span.delete { position: absolute; top: 0px; right: 0px; background: #333; height: 20px; line-height: 20px; font-size: 11px; padding: 0 3px; z-index: 1000; filter: alpha(opacity=70); -moz-opacity: 0.7; -khtml-opacity: 0.7; opacity: 0.7; color: #FFF; }
	
	.img { margin-top: 5px; } 
	.img div { width: 90px; height: 90px; border: 1px solid #ddd; float: left; position: relative; margin-right: 8px; }
	.img div img { width: 90px; height: 90px; position: absolute; }
	.img div span { width: 90px; display: block; height: 20px; line-height: 20px; text-align: center; position: absolute; top: 70px; background: #000; color: #fff; font-size: 12px; filter: alpha(opacity=70); -moz-opacity: 0.7; -khtml-opacity: 0.7; opacity: 0.7; cursor: pointer; }
	.menu_config_list { display: none; }
	.menu_config_list_0 { display: block; }
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
  	<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
	<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
    <div class="r_nav">
      <ul>
        <li><a href="config.php">基本设置</a></li>
        <li><a href="skin.php">风格设置</a></li>
        <li><a href="home.php">首页设置</a></li>
        <li class="cur"><a href="home.php">菜单配置</a></li>
      </ul>
    </div>

    <div id="home" class="r_con_wrap">
    	<div class="menu_list">
    	<?php foreach ($ShopMenu['menu'] as $k => $v) : ?>
		  	<a href="javascript:void(0);" onclick="show_tabs_contents(<?php echo $k; ?>)" <?php if($k == 0): ?> class="current" <?php endif; ?>><?php echo $v['menu_name']; ?><span class="arrow"></span><?php if($k > 0) : ?><span class="delete" data-id="<?php echo $k; ?>">删除</span><?php endif; ?></a>
		<?php endforeach; ?>

		<?php if (count($ShopMenu['menu']) < 6) : ?>
			<a href="javascript:void(0);" onclick="show_tabs_contents('add')" style="font-size:24px;">＋</a>
		<?php endif; ?>
		</div>
		<div class="m_righter">
		  
		  <h1>设置商城前台导航菜单配置</h1>
          <form action="?" name="category_form" id="category_form" class="r_con_form" method="post">
		  <?php foreach ($ShopMenu['menu'] as $k => $v) : ?>
          	<!--配置项列表start-->
            <div class="menu_config_list menu_config_list_<?php echo $k; ?>">
	            <div class="opt_item rows">
	              <label>菜单名称：</label>
	              <span class="input"><input type="text" name="menu[<?php echo $k; ?>][menu_name]" value="<?php echo $v['menu_name']; ?>" class="form_input" size="5" maxlength="30" notnull /><font class="fc_red">*</font>前台首页展示名称，不设置则使用系统默认名称；</span>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>登录状态名称：</label>
	              <span class="input"><input type="text" name="menu[<?php echo $k; ?>][login_menu_name]" value="<?php if(isset($v['login_menu_name'])) { echo $v['login_menu_name']; } ?>" class="form_input" size="5" maxlength="30" notnull />  用户登录之后将使用该名称;不更改无需填写</span>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>菜单图标：</label>
	              <span class="input"><div class="up_input"><input type="button" class="ImgUpload" data-id="<?php echo $k; ?>" value="添加图片" style="width:80px;" /></div>
		          <div class="tips">请上传前台显示的当前菜单图标</div>
				  <div class="img PicDetail">
				  	<?php if (isset($v['icon']) && !empty($v['icon'])) : ?>
						<div><a href="<?php echo $v['icon']; ?>" target="_blank"><img src="<?php echo $v['icon']; ?>" /></a> <span>删除</span><input type="hidden" name="menu[<?php echo $k; ?>][icon]" value="<?php echo $v['icon']; ?>" /></div>
				  	<?php endif; ?>
				  </div>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>链接地址：</label>
	              <span class="input"><input type="text" name="menu[<?php echo $k; ?>][menu_href]" value="<?php echo $v['menu_href']; ?>" class="form_input long_form_input" size="5" maxlength="150" notnull /><font class="fc_red">*</font>点击菜单将跳转到该地址</span>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>登陆链接地址：</label>
	              <span class="input"><input type="text" name="menu[<?php echo $k; ?>][login_menu_href]" value="<?php echo $v['login_menu_href']; ?>" class="form_input long_form_input" size="5" maxlength="150" notnull />用户登录成功菜单的链接地址;</span>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>绑定属性：</label>
	              <span class="input">
	              	<select name="menu[<?php echo $k; ?>][bind_action_attr]">
	              		<option value="0" <?php if($v['bind_action_attr'] == 0): ?>selected="selected"<?php endif; ?>>不绑定</option>
	              		<option value="1" <?php if($v['bind_action_attr'] == 1): ?>selected="selected"<?php endif; ?>>绑定分销模块</option>
	              		<option value="2" <?php if($v['bind_action_attr'] == 2): ?>selected="selected"<?php endif; ?>>绑定购物车模块</option>
	              	</select>
	              </span>
	              <div class="clear"></div>
	            </div>

	            <!--<div class="opt_item rows">
	              <label>排序：</label>
	              <span class="input"><input type="hidden" name="menu[<?php echo $k; ?>][menu_order]" value="<?php //echo $v['menu_order']; ?>" class="form_input" size="5" maxlength="30" notnull /><font class="fc_red">*</font>请输入排序数字</span>
	              <div class="clear"></div>
	            </div>-->
	        </div><!--配置项列表end-->
		  <?php endforeach; ?>

		  <?php if (count($ShopMenu['menu']) < 6) : ?>
		  	<!--配置项列表start-->
            <div class="menu_config_list menu_config_list_add">
	            <div class="opt_item rows">
	              <label>菜单名称：</label>
	              <span class="input"><input type="text" name="menu[<?php echo count($ShopMenu['menu']); ?>][menu_name]" value="" class="form_input" size="5" maxlength="30" notnull /><font class="fc_red">*</font>前台首页展示名称，不设置则使用系统默认名称；</span>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>登录状态名称：</label>
	              <span class="input"><input type="text" name="menu[<?php echo count($ShopMenu['menu']); ?>][login_menu_name]" value="" class="form_input" size="5" maxlength="30" notnull />  用户登录之后将使用该名称;不更改无需填写</span>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>菜单图标：</label>
	              <span class="input"><div class="up_input"><input type="button" class="ImgUpload" data-id="<?php echo count($ShopMenu['menu']); ?>" value="添加图片" style="width:80px;" /></div>
		          <div class="tips">请上传前台显示的当前菜单图标</div>
				  <div class="img PicDetail">
				  	
				  </div>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>链接地址：</label>
	              <span class="input"><input type="text" name="menu[<?php echo count($ShopMenu['menu']); ?>][menu_href]" value="" class="form_input long_form_input" size="5" maxlength="150" notnull /><font class="fc_red">*</font>点击菜单将跳转到该地址</span>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>登陆链接地址：</label>
	              <span class="input"><input type="text" name="menu[<?php echo count($ShopMenu['menu']); ?>][login_menu_href]" value="" class="form_input long_form_input" size="5" maxlength="150" notnull />用户登录成功菜单的链接地址;</span>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>绑定属性：</label>
	              <span class="input">
	              	<select name="menu[<?php echo count($ShopMenu['menu']); ?>][bind_action_attr]">
	              		<option value="0">不绑定</option>
	              		<option value="1">绑定分销模块</option>
	              		<option value="2">绑定购物车模块</option>
	              	</select>
	              </span>
	              <div class="clear"></div>
	            </div>

	            <div class="opt_item rows">
	              <label>排序：</label>
	              <span class="input"><input type="text" name="menu[<?php echo count($ShopMenu['menu']); ?>][menu_order]" value="" class="form_input" size="5" maxlength="30" notnull /><font class="fc_red">*</font>请输入排序数字</span>
	              <div class="clear"></div>
	            </div>
	        </div><!--配置项列表end-->
	      <?php endif; ?>

			<div class="opt_item rows no-border">
				<label></label>
				<span class="input no-border"><input type="submit" class="btn_green" value="提交保存"></span>
				<div class="clear"></div>
			</div>

          </form>
        </div>

    </div>
<script>
KindEditor.ready(function(K) {
	K.create('textarea[name="Description"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=web_column&Users_ID=<?php echo $_SESSION["Users_ID"];?>',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
	
	});
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=web_article',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('.ImgUpload').click(function(){
		var picBoxObj = $(this).parent('div.up_input').next('div.tips').next('div.PicDetail');
		var num = $(this).attr('data-id');
		if(picBoxObj.children().length>=1){
			if (!confirm('你已经上传过icon图片了，是否重新上传！')) { return false; };
		}

		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align) {
					picBoxObj.html('<div><a href="'+url+'" target="_blank"><img src="'+url+'" /></a> <span>删除</span><input type="hidden" name="menu['+num+'][icon]" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('.PicDetail').click(function(){
		K(this).children('div').remove();
	});
})

$(function(){
	$('.menu_list a span.delete').click(function(){
		if (confirm('确定删除该菜单？删除后将无法恢复！')) {
			menu_id = $(this).attr('data-id');
			$.post('?', {'menuId':menu_id, 'method' : 'ajaxpost'}, function(data){
				alert(data.msg);
				window.location.reload();
			}, 'json');
		};
	});	
});

function show_tabs_contents(numId)
{
	$('.menu_list a').removeClass('current');
	$('.menu_list a').each(function(index){
		if(index == numId) {
			$(this).addClass('current');
		}
	});
	$('.menu_config_list').hide();
	$('.menu_config_list_'+numId).show();
}
</script>
  </div>
</div>
</body>
</html>
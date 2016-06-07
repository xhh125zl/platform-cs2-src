<?php
if($rsSkin){
	$Home_Json=json_decode($rsSkin['Home_Json'],true);
	$do_action=empty($_POST['do_action'])?'':$_POST['do_action'];
	if($rsConfig['Skin_ID']==1){
		if($do_action=='web.home_diy'){
			$Data=array(
				"Home_Json"=>str_replace('undefined','',$_POST["gruopPackage"])
			);
			$Flag=$DB->Set("web_home",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Skin_ID=".$rsConfig['Skin_ID']);
			if($Flag){
				$json=array(
					"status"=>"1"
				);
				echo json_encode($json);
			}else{
				$json=array(
					"status"=>"0"
				);
				echo json_encode($json);
			}
			exit;
		}
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
<script type='text/javascript' src='/third_party/uploadify/jquery.uploadify.min.js'></script>
<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-diy.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
var editor;
KindEditor.ready(function(K) {
	editor = K.create('textarea[name="content"]', {
		themeType : 'simple',
		filterMode : true,
		uploadJson : '/member/upload_json.php?TableField=web_home',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', 'undo', 'redo', '/', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
	});
})
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
<div class="iframe_content">
<link href='/static/member/css/web.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/member/js/web.js'></script>
<div class="r_nav">
  <ul>
    <li class=""><a href="config.php">基本设置</a></li>
        <li class=""><a href="skin.php">风格设置</a></li>
        <li class="cur"><a href="home.php">首页设置</a></li>
        <li class=""><a href="column.php">栏目管理</a></li>
        <li class=""><a href="lbs.php">一键导航</a></li>
  </ul>
</div>
<script type='text/javascript' src='/static/js/plugin/dragsort/ca_drag.js'></script> 
<script type='text/javascript' src='/static/js/plugin/dragsort/ca_orderDrag.js'></script> 
<script type='text/javascript' src='/static/js/plugin/colorpicker/js/colorpicker.js'></script>
<link href='/static/js/plugin/colorpicker/css/colorpicker.css' rel='stylesheet' type='text/css' />
<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
<script type="text/javascript">
var myeditor = "";
var pName    = ""; //全局变量记录当前使用组件名称
$(function(){
	web_obj.diy_init();
	web_obj.colorPicker();
});
</script>
<div class="r_con_config r_con_wrap"> 
  <!-- 组件栏start -->
  <div class="package_sprite">
    <div class="sp1">组件</div>
    <div class="sp2"> 
      <!-- 图标start -->
      <div class="square_sprite" packageName="p0">
        <div class="square_img"><img src="/static/member/images/web/diy/l6.jpg" /><span></span></div>
        <div class="square_name">一行三列</div>
      </div>
      <div class="square_sprite" packageName="p1">
        <div class="square_img"><img src="/static/member/images/web/diy/l5.jpg" /><span></span></div>
        <div class="square_name">一行两列</div>
      </div>
      <div class="square_sprite" packageName="p2">
        <div class="square_img"><img src="/static/member/images/web/diy/l1.jpg" /><span></span></div>
        <div class="square_name">文字</div>
      </div>
      <div class="square_sprite" packageName="p3">
        <div class="square_img"><img src="/static/member/images/web/diy/l2.jpg" /><span></span></div>
        <div class="square_name">图片</div>
      </div>
      <div class="square_sprite" packageName="p4">
        <div class="square_img"><img src="/static/member/images/web/diy/l3.jpg" /><span></span></div>
        <div class="square_name">幻灯片</div>
      </div>
      <div class="square_sprite" packageName="p5">
        <div class="square_img"><img src="/static/member/images/web/diy/l4.jpg" /><span></span></div>
        <div class="square_name">电话拨号</div>
      </div>
      <!-- 图标end --> 
    </div>
  </div>
  <!-- 组件栏end --> 
  <!-- 视图start -->
  <div class="ipad_sprite">
    <div class="ipad isNon">
      <?php if(empty($Home_Json)){
		  echo '<div id="ipadNotice">拖动组件到中心区域</div>';
	  }else{
		  foreach($Home_Json as $key=>$value){
			  $url=explode('|',$value['url']);
			  $pic=explode('|',$value['pic']);
			  $txt=explode('|',$value['txt']);
			  if($value['type']=='p0'){
				  $txtColor=explode('|',$value['txtColor']);
				  $bgColor=explode('|',$value['bgColor']);
				  echo '<div class="p0 sprite1" packageName="p0" link0="'.$url[0].'" link1="'.$url[1].'" link2="'.$url[2].'" color0="'.$txtColor[0].'" color1="'.$txtColor[1].'" color2="'.$txtColor[2].'" background0="'.$bgColor[0].'" background1="'.$bgColor[1].'" background2="'.$bgColor[2].'">
				  <div class="dragPart">
					<div class="p0ImgFrame">
					  <div class="imgObj"><img src="'.$pic[0].'" width="95" /></div>
					  <div class="wordObj" style="color:; background:">'.$txt[0].'</div>
					  <div class="clean"></div>
					</div>
					<div class="p0ImgFrame">
					  <div class="imgObj"><img src="'.$pic[1].'" width="95" /></div>
					  <div class="wordObj" style="color:; background:">'.$txt[1].'</div>
					  <div class="clean"></div>
					</div>
					<div class="p0ImgFrame">
					  <div class="imgObj"><img src="'.$pic[2].'" width="95" /></div>
					  <div class="wordObj" style="color:; background:">'.$txt[2].'</div>
					  <div class="clean"></div>
					</div>
				  </div>
				  <div class="delObj hand" onclick="web_obj.delObjEvt(this, \'p0\');"><img  src="/static/member/images/web/diy/del.png" /></div>
				  <div class="clean"></div>
				</div>';
			  }elseif($value['type']=='p1'){
				  $txtColor=explode('|',$value['txtColor']);
				  $bgColor=explode('|',$value['bgColor']);
				  echo '<div class="p1 sprite1" packageName="p1" link0="'.$url[0].'" link1="'.$url[1].'" color0="'.$txtColor[0].'" color1="'.$txtColor[1].'" background0="'.$bgColor[0].'" background1="'.$bgColor[1].'">
				  <div class="dragPart">
					<div class="p1ImgFrame">
					  <div class="imgObj"><img src="'.$pic[0].'" width="146" /></div>
					  <div class="wordObj" style="color:; background:">'.$txt[0].'</div>
					  <div class="clean"></div>
					</div>
					<div class="p1ImgFrame">
					  <div class="imgObj"><img src="'.$pic[1].'" width="146" /></div>
					  <div class="wordObj" style="color:; background:">'.$txt[1].'</div>
					  <div class="clean"></div>
					</div>
				  </div>
				  <div class="delObj hand" onclick="web_obj.delObjEvt(this, \'p1\');"><img  src="/static/member/images/web/diy/del.png" /></div>
				  <div class="clean"></div>
				</div>';
			  }elseif($value['type']=='p2'){
				  $txt[0] = str_replace('&quot;','"',$txt[0]);
				  echo '<div class="p2 sprite1" packageName="p2" link0="">
				  <div class="dragPart">'.$txt[0].'</div>
				  <div class="delObj hand" onclick="web_obj.delObjEvt(this, \'p2\');"><img  src="/static/member/images/web/diy/del.png" /></div>
				</div>';
			  }elseif($value['type']=='p3'){
				  $txtColor=explode('|',$value['txtColor']);
				  $bgColor=explode('|',$value['bgColor']);
				  echo '<div class="p3 sprite1" packageName="p3" link0="'.$url[0].'" color0="'.$txtColor[0].'" background0="'.$bgColor[0].'">
				  <div class="dragPart">
					<div class="p3ImgFrame">
					  <div class="imgObj"><img src="'.$pic[0].'" width="292" /></div>
					  <div class="wordObj" style="color:; background:">'.$txt[0].'</div>
					  <div class="clean"></div>
					</div>
				  </div>
				  <div class="delObj hand" onclick="web_obj.delObjEvt(this, \'p3\');"><img  src="/static/member/images/web/diy/del.png" /></div>
				  <div class="clean"></div>
				</div>';
			  }elseif($value['type']=='p4'){
				  echo '<div class="p4 sprite1" packageName="p4" link0="'.$url[0].'" link1="'.$url[1].'" link2="'.$url[2].'" link3="'.$url[3].'" link4="'.$url[4].'">
				  <div class="dragPart">
					<div class="p4ImgFrame"><img width="292"'.($pic[0]=='undefined'?' style="display:none"':' src="'.$pic[0].'" style="display:block"').'/><img width="292"'.($pic[1]=='undefined'?' style="display:none"':' src="'.$pic[1].'" style="display:none"').'/><img width="292"'.($pic[2]=='undefined'?' style="display:none"':' src="'.$pic[2].'" style="display:none"').'/><img width="292"'.($pic[3]=='undefined'?' style="display:none"':' src="'.$pic[3].'" style="display:none"').'/><img width="292"'.($pic[4]=='undefined'?' style="display:none"':' src="'.$pic[4].'" style="display:none"').'/></div>
				  </div>
				  <div class="delObj hand" onclick="web_obj.delObjEvt(this, \'p4\');"><img  src="/static/member/images/web/diy/del.png" /></div>
				</div>';
			  }elseif($value['type']=='p5'){
					  echo '<div style="background:'.$value['bgColor'].'" class="p5 sprite1" packageName="p5" background0="'.$value['bgColor'].'" color0="'.$value['txtColor'].'" fontsize0="'.$value['fontSize'].'px" >
				  <div class="dragPart" style="color:'.$value['txtColor'].'; font-size:'.$value['fontSize'].'px;">'.$value['txt'].'</div>
				  <div class="delObj hand" onclick="web_obj.delObjEvt(this, \'p5\');"><img  src="/static/member/images/web/diy/del.png" /></div>
				</div>';
			  }
		  }
	  }?>
    </div>
  </div>
  <!-- 视图end --> 
  <!-- 属性start -->
  <div class="property_sprite">
    <div class="ps1">
      <div class="ps1_1"></div>
      <div class="ps1_2">组件属性编辑</div>
    </div>
    <div class="ps2">
      <div class="ps2Notice">组件属性编辑面板</div>
      <div class="ps2_frmae_p0">
        <div class="pNotice">注：图片高度保持一致(宽：213px，高：自定义)</div>
        <div class="clean"></div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile_p0_0" />
            </div>
          </div>
          <div class="clean"></div>
          <div class="img_name">
            <input type="text" value="文字区域1" onFocus="this.value=='文字区域1'?this.value='':''" />
          </div>
          <div class="colorTitle">颜色：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorWordp0_0" class="txtColor" value="#ffffff" readonly />
          <div class="colorTitle" style="margin-left:20px;">背景：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorBgp0_0" class="bgColor" value="#4C4C4C" readonly />
          <div class="clean"></div>
        </div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile_p0_1" />
            </div>
          </div>
          <div class="clean"></div>
          <div class="img_name">
            <input type="text" value="文字区域2" onFocus="this.value=='文字区域2'?this.value='':''" />
          </div>
          <div class="colorTitle">颜色：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorWordp0_1" class="txtColor" value="#ffffff" readonly />
          <div class="colorTitle" style="margin-left:20px;">背景：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorBgp0_1" class="bgColor" value="#4C4C4C" readonly />
          <div class="clean"></div>
        </div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile_p0_2" />
            </div>
          </div>
          <div class="clean"></div>
          <div class="img_name">
            <input type="text" value="文字区域3" onFocus="this.value=='文字区域3'?this.value='':''" />
          </div>
          <div class="colorTitle">颜色：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorWordp0_2" class="txtColor" value="#ffffff" readonly />
          <div class="colorTitle" style="margin-left:20px;">背景：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorBgp0_2" class="bgColor" value="#4C4C4C" readonly />
          <div class="clean"></div>
        </div>
        <div class='btn_green btn_w_120 editBtn hand' onclick='web_obj.p0.insertWord("p0")'>保 存</div>
        <div class="clean"></div>
      </div>
      <div class="ps2_frmae_p1">
        <div class="pNotice">注：图片高度保持一致(宽：320px，高：自定义)</div>
        <div class="clean"></div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile_p1_0" />
            </div>
            <div class="uploadNotice"></div>
          </div>
          <div class="clean"></div>
          <div class="img_name">
            <input type="text" value="文字区域1" onFocus="this.value=='文字区域1'?this.value='':''" />
          </div>
          <div class="colorTitle">颜色：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorWordp1_0" class="txtColor" value="#ffffff" readonly />
          <div class="colorTitle" style="margin-left:20px;">背景：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorBgp1_0" class="bgColor" value="#4C4C4C" readonly />
          <div class="clean"></div>
        </div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile_p1_1" />
            </div>
            <div class="uploadNotice"></div>
          </div>
          <div class="clean"></div>
          <div class="img_name">
            <input type="text" value="文字区域2" onFocus="this.value=='文字区域2'?this.value='':''" />
          </div>
          <div class="colorTitle">颜色：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorWordp1_1" class="txtColor" value="#ffffff" readonly />
          <div class="colorTitle" style="margin-left:20px;">背景：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorBgp1_1" class="bgColor" value="#4C4C4C" readonly />
          <div class="clean"></div>
        </div>
        <div class='btn_green btn_w_120 editBtn hand' onclick='web_obj.p1.insertWord("p1")'>保 存</div>
        <div class="clean"></div>
      </div>
      <div class="ps2_frmae_p2"> 
        <div class="editorSprite">
          <textarea name="content" id="content" style="width:100%; height:300px;"></textarea>
        </div>
        <div class='btn_green btn_w_120 editBtn hand' onclick='web_obj.p2.insertHtml("p2")'>保 存</div>
        <div class="clean"></div>
      </div>
      <div class="ps2_frmae_p3">
        <div class="pNotice">注：图片高度保持一致(宽：640px，高：自定义)</div>
        <div class="clean"></div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div class="clean"></div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile" />
            </div>
          </div>
          <div class="clean"></div>
          <div class="img_name">
            <input type="text" value="添加文字" onFocus="this.value=='添加文字'?this.value='':''" />
          </div>
          <div class="colorTitle">文字颜色：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorWordp3_0" class="txtColor" value="#ffffff" readonly />
          <div class="colorTitle" style="margin-left:20px;">文字背景颜色：</div>
          <input type="text" maxlength="8" size="8" id="colorSelectorBgp3_0" class="bgColor" value="#4C4C4C" readonly />
          <div class="clean"></div>
        </div>
        <div class='btn_green btn_w_120 editBtn hand' onclick='web_obj.p3.insertWord("p3")'>保 存</div>
        <div class="clean"></div>
      </div>
      <div class="ps2_frmae_p4">
        <div class="pNotice">注：图片高度保持一致(宽：640px，高：自定义)</div>
        <div class="clean"></div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile_p4_0" />
            </div>
            <div id="p4LookDetail0" class="lookDetail"></div>
          </div>
          <div class="clean"></div>
        </div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile_p4_1" />
            </div>
            <div id="p4LookDetail1" class="lookDetail"></div>
          </div>
          <div class="clean"></div>
        </div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile_p4_2" />
            </div>
            <div id="p4LookDetail2" class="lookDetail"></div>
          </div>
          <div class="clean"></div>
        </div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile_p4_3" />
            </div>
            <div id="p4LookDetail3" class="lookDetail"></div>
          </div>
          <div class="clean"></div>
        </div>
        <div class="warp_packges">
          <div class="selectLink">链接地址：
            <select name='Url'>
              <?php UrlList(); ?>
            </select>
          </div>
          <div  class="wrap_upload">
            <div class="uploadBtn hand">
              <input type="file" id="upfile_p4_4" />
            </div>
            <div id="p4LookDetail4" class="lookDetail"></div>
          </div>
          <div class="clean"></div>
        </div>
        <div class='btn_green btn_w_120 editBtn hand' onclick='web_obj.p4.insertWord("p4")'>保 存</div>
        <div class="clean"></div>
      </div>
      <div class="ps2_frmae_p5">
        <div class="pNotice">注：一行填写一个号码，如：<span class="fc_red">13800138000/陈生</span></div>
        <div class="clean"></div>
        <div class="phoneSprite">
          <textarea></textarea>
        </div>
        <div class="clean"></div>
        <div class="colorTitle">颜色：</div>
        <input type="text" maxlength="8" size="8" id="colorSelectorWordp5" class="txtColor" value="#ffffff" readonly />
        <div class="colorTitle" style="margin-left:20px;">背景：</div>
        <input type="text" maxlength="8" size="8" id="colorSelectorBgp5" class="bgColor" value="#4C4C4C" readonly />
        <div class="colorTitle" style="margin-left:20px;">大小：</div>
        <select class="fontSize_p5" style="vertical-align:-5px;">
          <option value="14">14px</option>
          <option value="16">16px</option>
          <option value="18">18px</option>
          <option value="20">20px</option>
          <option value="22">22px</option>
          <option value="24">24px</option>
        </select>
        <div class="clean"></div>
        <div class='btn_green btn_w_120 editBtn hand' onclick='web_obj.p5.insertHtml("p5")'>保 存</div>
        <div class="clean"></div>
      </div>
    </div>
  </div>
  <!-- 属性end -->
  <div class="clear"></div>
</div>
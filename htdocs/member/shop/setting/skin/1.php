<?php
if($_POST){
	$do_action=empty($_POST['do_action'])?'':$_POST['do_action'];
	if($do_action=='shop.home_diy'){
		$Data=array(
			"Home_Json"=>str_replace('undefined','',$_POST["gruopPackage"])
		);
		$Flag=$DB->Set("shop_home",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Skin_ID=".$rsConfig['Skin_ID']);
		if($Flag){
			$response=array(
				"status"=>"1"
			);
		}else{
			$response=array(
				"status"=>"0"
			);
		}
		echo json_encode($response);
		exit;
	}
}

function UrlList(){
	global $DB;
	echo '<option value="">--请选择--</option>
	<optgroup label="------------------系统业务模块------------------"></optgroup>';
	$DB->get("wechat_material","Material_ID,Material_Table,Material_Json","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table<>'0' and Material_TableID=0 and Material_Display=0 order by Material_ID desc");
	while($rsMaterial=$DB->fetch_assoc()){
		$Material_Json=json_decode($rsMaterial['Material_Json'],true);
		echo '<option value="/api/'.$_SESSION["Users_ID"].'/'.$rsMaterial['Material_Table'].'/">'.$Material_Json['Title'].'</option>';
	}
	echo '<optgroup label="------------------微商城产品分类页面------------------"></optgroup>';
	$DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ParentID=0 order by Category_Index asc");
	$ParentCategory=array();
	$i=1;
	while($rsPCategory=$DB->fetch_assoc()){
		$ParentCategory[$i]=$rsPCategory;
		$i++;
	}
	foreach($ParentCategory as $key=>$value){
		$DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ParentID=".$value["Category_ID"]." order by Category_Index asc");
		if($DB->num_rows()>0){
			echo '<option value="/api/'.$_SESSION["Users_ID"].'/shop/category/'.$value["Category_ID"].'/">'.$value["Category_Name"].'</option>';
			while($rsCategory=$DB->fetch_assoc()){
				echo '<option value="/api/'.$_SESSION["Users_ID"].'/shop/category/'.$rsCategory["Category_ID"].'/">&nbsp;&nbsp;├'.$rsCategory["Category_Name"].'</option>';
			}
		}else{
			echo '<option value="/api/'.$_SESSION["Users_ID"].'/shop/category/'.$value["Category_ID"].'/">'.$value["Category_Name"].'</option>';
		}
	}
	
	echo '<optgroup label="------------------自定义URL------------------"></optgroup>';
	$DB->get("wechat_url","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	while($rsUrl=$DB->fetch_assoc()){
		echo '<option value="'.$rsUrl['Url_Value'].'">'.$rsUrl['Url_Name'].'('.$rsUrl['Url_Value'].')</option>';
	}
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
<script src="/third_party/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <!--<li class=""><a href="other_config.php">活动设置</a></li>-->
        <li class=""><a href="distribute_config.php">分销设置</a></li>
        <li class=""><a href="skin.php">风格设置</a></li>
        <li class="cur"><a href="home.php">首页设置</a></li>
      </ul>
    </div>
	<div style="width:100%; background:#FFF; padding:10px 0px">
		<span onclick="openwin();" style="display:block; cursor:pointer; width:100px; height:36px; line-height:36px; margin:0px 0px 0px 660px; background:#3AA0EB; color:#FFF; text-align:center; font-size:14px; border-radius:5px;">首页预览</span>
		<script language="javascript">
		function openwin(){
			var win_top = ($(window).height()-750)/2;
			var win_left = ($(window).width()-480)/2;
			window.open('/api/<?php echo $_SESSION["Users_ID"];?>/shop/','','height=750,width=480,top='+win_top+',left='+win_left+',toolbar=no,menubar=no,scrollbars=yes,resizable=no,location=no,status=no');
		}
		</script>
	</div>
    <link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
    <script type='text/javascript' src="/third_party/kindeditor/kindeditor-diy.js"></script>
    <script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
    <script>
    var editor;
    KindEditor.ready(function(K) {
        editor = K.create('textarea[name="content"]', {
            themeType : 'simple',
            filterMode : true,
            uploadJson : '/member/upload_json.php?TableField=shop_home',
            fileManagerJson : '/member/file_manager_json.php',
            allowFileManager : true,
            items : [
                'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', 'undo', 'redo', '/', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
        });
    })
    </script>
	<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
	<script type='text/javascript' src='/static/js/plugin/dragsort/ca_drag_shop.js'></script> 
    <script type='text/javascript' src='/static/js/plugin/dragsort/ca_orderDrag_shop.js'></script> 
    <script type='text/javascript' src='/static/js/plugin/colorpicker/js/colorpicker.js'></script>
    <link href='/static/js/plugin/colorpicker/css/colorpicker.css' rel='stylesheet' type='text/css' />
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script type="text/javascript">
    var myeditor = "";
    var pName    = ""; //全局变量记录当前使用组件名称
    $(function(){
        shop_obj.diy_init();
        shop_obj.colorPicker();
    });
    </script>
	<div class="r_con_config r_con_wrap"> 
  		<!-- 组件栏start -->
        <div class="package_sprite">
            <div class="sp1">组件</div>
            <div class="sp2"> 
              <!-- 图标start -->
              <div class="square_sprite" packageName="p1">
                <div class="square_img"><img src="/static/member/images/shop/diy/l5.jpg" /><span></span></div>
                <div class="square_name">一行两列</div>
              </div>
              <div class="square_sprite" packageName="p0">
                <div class="square_img"><img src="/static/member/images/shop/diy/l6.jpg" /><span></span></div>
                <div class="square_name">一行三列</div>
              </div>              
              <div class="square_sprite" packageName="p6">
                <div class="square_img"><img src="/static/member/images/shop/diy/l7.jpg" /><span></span></div>
                <div class="square_name">一行四列</div>
              </div>
              <div class="square_sprite" packageName="p7">
                <div class="square_img"><img src="/static/member/images/shop/diy/l8.jpg" /><span></span></div>
                <div class="square_name">一行五列</div>
              </div>
              <div class="square_sprite" packageName="p8">
                <div class="square_img"><img src="/static/member/images/shop/diy/l9.jpg" /><span></span></div>
                <div class="square_name">左一右二</div>
              </div>
              <div class="square_sprite" packageName="p9">
                <div class="square_img"><img src="/static/member/images/shop/diy/l10.jpg" /><span></span></div>
                <div class="square_name">左一右四</div>
              </div>
              <div class="square_sprite" packageName="p10">
                <div class="square_img"><img src="/static/member/images/shop/diy/l11.jpg" /><span></span></div>
                <div class="square_name">左二右一</div>
              </div>
              <div class="square_sprite" packageName="p11">
                <div class="square_img"><img src="/static/member/images/shop/diy/l12.jpg" /><span></span></div>
                <div class="square_name">左四右一</div>
              </div>
              <div class="square_sprite" packageName="p2">
                <div class="square_img"><img src="/static/member/images/shop/diy/l1.jpg" /><span></span></div>
                <div class="square_name">文字</div>
              </div>
              <div class="square_sprite" packageName="p3">
                <div class="square_img"><img src="/static/member/images/shop/diy/l2.jpg" /><span></span></div>
                <div class="square_name">图片</div>
              </div>
              <div class="square_sprite" packageName="p4">
                <div class="square_img"><img src="/static/member/images/shop/diy/l3.jpg" /><span></span></div>
                <div class="square_name">幻灯片</div>
              </div>
              <div class="square_sprite" packageName="p5">
                <div class="square_img"><img src="/static/member/images/shop/diy/l4.jpg" /><span></span></div>
                <div class="square_name">电话拨号</div>
              </div>
			  <div class="square_sprite" packageName="p12">
                <div class="square_img"><img src="/static/member/images/shop/diy/l13.jpg" /><span></span></div>
                <div class="square_name">搜索框</div>
              </div>
              <!-- 图标end --> 
            </div>
        </div>
  		<!-- 组件栏end --> 
  		<!-- 视图start -->
        <div class="ipad_sprite">
            <div class="ipad isNon">
              <?php if(empty($json)){
                  echo '<div id="ipadNotice">拖动组件到中心区域</div>';
              }else{
                  foreach($json as $key=>$value){
                      $url=explode('|',$value['url']);
                      $pic=explode('|',$value['pic']);
                      $txt=explode('|',$value['txt']);
					  if($value['type']=='p1'){//一行两列
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
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p1\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                          <div class="clean"></div>
                        </div>';
                      }elseif($value['type']=='p0'){//一行三列
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
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p0\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                          <div class="clean"></div>
                        </div>';
					  }elseif($value['type']=='p6'){//一行四列
						  $txtColor=explode('|',$value['txtColor']);
                          $bgColor=explode('|',$value['bgColor']);
                          echo '<div class="p6 sprite1" packageName="p6" link0="'.$url[0].'" link1="'.$url[1].'" link2="'.$url[2].'" link3="'.$url[3].'" color0="'.$txtColor[0].'" color1="'.$txtColor[1].'" color2="'.$txtColor[2].'" color3="'.$txtColor[3].'" background0="'.$bgColor[0].'" background1="'.$bgColor[1].'" background2="'.$bgColor[2].'" background3="'.$bgColor[3].'">
                          <div class="dragPart">
                            <div class="p6ImgFrame">
                              <div class="imgObj"><img src="'.$pic[0].'" width="73" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[0].'</div>
                              <div class="clean"></div>
                            </div>
                            <div class="p6ImgFrame">
                              <div class="imgObj"><img src="'.$pic[1].'" width="73" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[1].'</div>
                              <div class="clean"></div>
                            </div>
                            <div class="p6ImgFrame">
                              <div class="imgObj"><img src="'.$pic[2].'" width="73" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[2].'</div>
                              <div class="clean"></div>
                            </div>
							<div class="p6ImgFrame">
                              <div class="imgObj"><img src="'.$pic[3].'" width="73" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[3].'</div>
                              <div class="clean"></div>
                            </div>
                          </div>
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p6\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                          <div class="clean"></div>
                        </div>';
					  }elseif($value['type']=='p7'){//一行五列
						  $txtColor=explode('|',$value['txtColor']);
                          $bgColor=explode('|',$value['bgColor']);
                          echo '<div class="p7 sprite1" packageName="p7" link0="'.$url[0].'" link1="'.$url[1].'" link2="'.$url[2].'" link3="'.$url[3].'" link4="'.$url[4].'" color0="'.$txtColor[0].'" color1="'.$txtColor[1].'" color2="'.$txtColor[2].'" color3="'.$txtColor[3].'" color4="'.$txtColor[4].'" background0="'.$bgColor[0].'" background1="'.$bgColor[1].'" background2="'.$bgColor[2].'" background3="'.$bgColor[3].'" background4="'.$bgColor[4].'">
                          <div class="dragPart">
                            <div class="p7ImgFrame">
                              <div class="imgObj"><img src="'.$pic[0].'" width="58" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[0].'</div>
                              <div class="clean"></div>
                            </div>
                            <div class="p7ImgFrame">
                              <div class="imgObj"><img src="'.$pic[1].'" width="58" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[1].'</div>
                              <div class="clean"></div>
                            </div>
                            <div class="p7ImgFrame">
                              <div class="imgObj"><img src="'.$pic[2].'" width="58" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[2].'</div>
                              <div class="clean"></div>
                            </div>
							<div class="p7ImgFrame">
                              <div class="imgObj"><img src="'.$pic[3].'" width="58" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[3].'</div>
                              <div class="clean"></div>
                            </div>
							<div class="p7ImgFrame">
                              <div class="imgObj"><img src="'.$pic[4].'" width="58" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[4].'</div>
                              <div class="clean"></div>
                            </div>
                          </div>
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p7\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                          <div class="clean"></div>
                        </div>';
					  }elseif($value['type']=='p8'){//左一右二
                          $txtColor=explode('|',$value['txtColor']);
                          $bgColor=explode('|',$value['bgColor']);
                          echo '<div class="p8 sprite1" packageName="p8" link0="'.$url[0].'" link1="'.$url[1].'" link2="'.$url[2].'" color0="'.$txtColor[0].'" color1="'.$txtColor[1].'" color2="'.$txtColor[2].'" background0="'.$bgColor[0].'" background1="'.$bgColor[1].'" background2="'.$bgColor[2].'">
                          <div class="dragPart">
						   <div class="left8">
                            <div class="p8ImgFrame">
                              <div class="imgObj"><img src="'.$pic[0].'" width="146" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[0].'</div>
                              <div class="clean"></div>
                            </div>
						   </div>
						   <div class="right8">
                            <div class="p8ImgFrame">
                              <div class="imgObj"><img src="'.$pic[1].'" width="146" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[1].'</div>
                              <div class="clean"></div>
                            </div>
                            <div class="p8ImgFrame">
                              <div class="imgObj"><img src="'.$pic[2].'" width="146" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[2].'</div>
                              <div class="clean"></div>
                            </div>
						   </div>
                          </div>
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p8\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                          <div class="clean"></div>
                        </div>';
					  }elseif($value['type']=='p9'){//左一右四
                          $txtColor=explode('|',$value['txtColor']);
                          $bgColor=explode('|',$value['bgColor']);
                          echo '<div class="p9 sprite1" packageName="p9" link0="'.$url[0].'" link1="'.$url[1].'" link2="'.$url[2].'" link3="'.$url[3].'" link4="'.$url[4].'" color0="'.$txtColor[0].'" color1="'.$txtColor[1].'" color2="'.$txtColor[2].'" color3="'.$txtColor[3].'" color4="'.$txtColor[4].'" background0="'.$bgColor[0].'" background1="'.$bgColor[1].'" background2="'.$bgColor[2].'" background3="'.$bgColor[3].'" background4="'.$bgColor[4].'">
                          <div class="dragPart">
						   <div class="left9">
                            <div class="p9ImgFrame">
                              <div class="imgObj"><img src="'.$pic[0].'" width="95" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[0].'</div>
                              <div class="clean"></div>
                            </div>
						   </div>
						   <div class="right9">
                            <div class="p9ImgFrame">
                              <div class="imgObj"><img src="'.$pic[1].'" width="95" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[1].'</div>
                              <div class="clean"></div>
                            </div>
                            <div class="p9ImgFrame">
                              <div class="imgObj"><img src="'.$pic[2].'" width="95" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[2].'</div>
                              <div class="clean"></div>
                            </div>
							<div class="p9ImgFrame">
                              <div class="imgObj"><img src="'.$pic[3].'" width="95" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[3].'</div>
                              <div class="clean"></div>
                            </div>
							<div class="p9ImgFrame">
                              <div class="imgObj"><img src="'.$pic[4].'" width="95" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[4].'</div>
                              <div class="clean"></div>
                            </div>
							<div class="clean"></div>
						   </div>
                          </div>
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p9\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                          <div class="clean"></div>
                        </div>';
					  }elseif($value['type']=='p10'){//左二右一
                          $txtColor=explode('|',$value['txtColor']);
                          $bgColor=explode('|',$value['bgColor']);
                          echo '<div class="p10 sprite1" packageName="p10" link0="'.$url[0].'" link1="'.$url[1].'" link2="'.$url[2].'" color0="'.$txtColor[0].'" color1="'.$txtColor[1].'" color2="'.$txtColor[2].'" background0="'.$bgColor[0].'" background1="'.$bgColor[1].'" background2="'.$bgColor[2].'">
                          <div class="dragPart">
						   <div class="left10">
                            <div class="p10ImgFrame">
                              <div class="imgObj"><img src="'.$pic[0].'" width="146" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[0].'</div>
                              <div class="clean"></div>
                            </div>
							<div class="p10ImgFrame">
                              <div class="imgObj"><img src="'.$pic[1].'" width="146" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[1].'</div>
                              <div class="clean"></div>
                            </div>
						   </div>
						   <div class="right10">
                            <div class="p10ImgFrame">
                              <div class="imgObj"><img src="'.$pic[2].'" width="146" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[2].'</div>
                              <div class="clean"></div>
                            </div>
						   </div>
                          </div>
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p10\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                          <div class="clean"></div>
                        </div>';
					  }elseif($value['type']=='p11'){//左四右一
                          $txtColor=explode('|',$value['txtColor']);
                          $bgColor=explode('|',$value['bgColor']);
                          echo '<div class="p11 sprite1" packageName="p11" link0="'.$url[0].'" link1="'.$url[1].'" link2="'.$url[2].'" link3="'.$url[3].'" link4="'.$url[4].'" color0="'.$txtColor[0].'" color1="'.$txtColor[1].'" color2="'.$txtColor[2].'" color3="'.$txtColor[3].'" color4="'.$txtColor[4].'" background0="'.$bgColor[0].'" background1="'.$bgColor[1].'" background2="'.$bgColor[2].'" background3="'.$bgColor[3].'" background4="'.$bgColor[4].'">
                          <div class="dragPart">
						   <div class="left11">
                            <div class="p11ImgFrame">
                              <div class="imgObj"><img src="'.$pic[0].'" width="95" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[0].'</div>
                              <div class="clean"></div>
                            </div>
							<div class="p11ImgFrame">
                              <div class="imgObj"><img src="'.$pic[1].'" width="95" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[1].'</div>
                              <div class="clean"></div>
                            </div>
                            <div class="p11ImgFrame">
                              <div class="imgObj"><img src="'.$pic[2].'" width="95" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[2].'</div>
                              <div class="clean"></div>
                            </div>
							<div class="p11ImgFrame">
                              <div class="imgObj"><img src="'.$pic[3].'" width="95" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[3].'</div>
                              <div class="clean"></div>
                            </div>
						   </div>
						   <div class="right11">
							<div class="p11ImgFrame">
                              <div class="imgObj"><img src="'.$pic[4].'" width="95" /></div>
                              <div class="wordObj" style="color:; background:">'.$txt[4].'</div>
                              <div class="clean"></div>
                            </div>
							<div class="clean"></div>
						   </div>
                          </div>
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p11\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                          <div class="clean"></div>
                        </div>';
                      }elseif($value['type']=='p2'){//文字
                          $txt[0] = str_replace('&quot;','"',$txt[0]);
                          echo '<div class="p2 sprite1" packageName="p2" link0="">
                          <div class="dragPart">'.$txt[0].'</div>
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p2\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                        </div>';
                      }elseif($value['type']=='p3'){//图片
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
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p3\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                          <div class="clean"></div>
                        </div>';
                      }elseif($value['type']=='p4'){//幻灯片
                          echo '<div class="p4 sprite1" packageName="p4" link0="'.$url[0].'" link1="'.$url[1].'" link2="'.$url[2].'" link3="'.$url[3].'" link4="'.$url[4].'">
                          <div class="dragPart">
                            <div class="p4ImgFrame"><img width="292"'.($pic[0]=='undefined'?' style="display:none"':' src="'.$pic[0].'" style="display:block"').'/><img width="292"'.($pic[1]=='undefined'?' style="display:none"':' src="'.$pic[1].'" style="display:none"').'/><img width="292"'.($pic[2]=='undefined'?' style="display:none"':' src="'.$pic[2].'" style="display:none"').'/><img width="292"'.($pic[3]=='undefined'?' style="display:none"':' src="'.$pic[3].'" style="display:none"').'/><img width="292"'.($pic[4]=='undefined'?' style="display:none"':' src="'.$pic[4].'" style="display:none"').'/></div>
                          </div>
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p4\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                        </div>';
                      }elseif($value['type']=='p5'){//电话拨号
                          echo '<div style="background:'.$value['bgColor'].'" class="p5 sprite1" packageName="p5" background0="'.$value['bgColor'].'" color0="'.$value['txtColor'].'" fontsize0="'.$value['fontSize'].'px" >
                          <div class="dragPart" style="color:'.$value['txtColor'].'; font-size:'.$value['fontSize'].'px;">'.$value['txt'].'</div>
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p5\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                        </div>';
                      }elseif($value['type']=='p12'){//搜索框
                          $txtColor=explode('|',$value['txtColor']);
                          $bgColor=explode('|',$value['bgColor']);
                          echo '<div class="p12 sprite1" packageName="p12" color0="'.$txtColor[0].'" background0="'.$bgColor[0].'">
                          <div class="dragPart">
                            <div class="p12ImgFrame" style="background:'.$bgColor[0].'">
                              <div class="submit"><img src="'.$pic[0].'" /></div>
                              <input type="text" class="input" style="border:1px solid '.$txtColor[0].'" />
                              <div class="clean"></div>
                            </div>
                          </div>
                          <div class="delObj hand" onclick="shop_obj.delObjEvt(this, \'p12\');"><img  src="/static/member/images/shop/diy/del.png" /></div>
                          <div class="clean"></div>
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
          
          <!--一行两列-->
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
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p1.insertWord("p1")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!--一行三列-->
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
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p0.insertWord("p0")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!--一行四列-->
          <div class="ps2_frmae_p6">
            <div class="pNotice">注：图片高度保持一致(宽：160px，高：自定义)</div>
            <div class="clean"></div>
            <div class="warp_packges">
              <div class="selectLink">链接地址：
                <select name='Url'>
                  <?php UrlList(); ?>
                </select>
              </div>
              <div  class="wrap_upload">
                <div class="uploadBtn hand">
                  <input type="file" id="upfile_p6_0" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name">
                <input type="text" value="文字区域1" onFocus="this.value=='文字区域1'?this.value='':''" />
              </div>
              <div class="colorTitle">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp6_0" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp6_0" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p6_1" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name">
                <input type="text" value="文字区域2" onFocus="this.value=='文字区域2'?this.value='':''" />
              </div>
              <div class="colorTitle">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp6_1" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp6_1" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p6_2" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name">
                <input type="text" value="文字区域3" onFocus="this.value=='文字区域3'?this.value='':''" />
              </div>
              <div class="colorTitle">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp6_2" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp6_2" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p6_3" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name">
                <input type="text" value="文字区域4" onFocus="this.value=='文字区域4'?this.value='':''" />
              </div>
              <div class="colorTitle">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp6_3" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp6_3" class="bgColor" value="#4C4C4C" readonly />
              <div class="clean"></div>
            </div>
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p6.insertWord("p6")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!--一行五列-->
          <div class="ps2_frmae_p7">
            <div class="pNotice">注：图片高度保持一致(宽：128px，高：自定义)</div>
            <div class="clean"></div>
            <div class="warp_packges">
              <div class="selectLink">链接地址：
                <select name='Url'>
                  <?php UrlList(); ?>
                </select>
              </div>
              <div  class="wrap_upload">
                <div class="uploadBtn hand">
                  <input type="file" id="upfile_p7_0" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name">
                <input type="text" value="文字区域1" onFocus="this.value=='文字区域1'?this.value='':''" />
              </div>
              <div class="colorTitle">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp7_0" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp7_0" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p7_1" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name">
                <input type="text" value="文字区域2" onFocus="this.value=='文字区域2'?this.value='':''" />
              </div>
              <div class="colorTitle">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp7_1" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp7_1" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p7_2" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name">
                <input type="text" value="文字区域3" onFocus="this.value=='文字区域3'?this.value='':''" />
              </div>
              <div class="colorTitle">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp7_2" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp7_2" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p7_3" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name">
                <input type="text" value="文字区域4" onFocus="this.value=='文字区域4'?this.value='':''" />
              </div>
              <div class="colorTitle">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp7_3" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp7_3" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p7_4" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name">
                <input type="text" value="文字区域5" onFocus="this.value=='文字区域5'?this.value='':''" />
              </div>
              <div class="colorTitle">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp7_4" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp7_4" class="bgColor" value="#4C4C4C" readonly />
              <div class="clean"></div>
            </div>
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p7.insertWord("p7")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!--左一右二-->
          <div class="ps2_frmae_p8">
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
                  <input type="file" id="upfile_p8_0" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域1" onFocus="this.value=='文字区域1'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp8_0" class="txtColor" value="#ffffff" readonly style="display:none" />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp8_0" class="bgColor" value="#4C4C4C" readonly style="display:none" />
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
                  <input type="file" id="upfile_p8_1" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域2" onFocus="this.value=='文字区域2'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp8_1" style="display:none" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px; display:none">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp8_1" style="display:none" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p8_2" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域3" onFocus="this.value=='文字区域3'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorWordp8_2" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorBgp8_2" class="bgColor" value="#4C4C4C" readonly />
              <div class="clean"></div>
            </div>
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p8.insertWord("p8")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!--左一右四-->
          <div class="ps2_frmae_p9">
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
                  <input type="file" id="upfile_p9_0" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域1" onFocus="this.value=='文字区域1'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp9_0" class="txtColor" value="#ffffff" readonly style="display:none" />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp9_0" class="bgColor" value="#4C4C4C" readonly style="display:none" />
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
                  <input type="file" id="upfile_p9_1" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域2" onFocus="this.value=='文字区域2'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp9_1" style="display:none" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px; display:none">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp9_1" style="display:none" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p9_2" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域3" onFocus="this.value=='文字区域3'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorWordp9_2" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorBgp9_2" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p9_3" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域4" onFocus="this.value=='文字区域4'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorWordp9_3" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorBgp9_3" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p9_4" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域5" onFocus="this.value=='文字区域5'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorWordp9_4" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorBgp9_4" class="bgColor" value="#4C4C4C" readonly />
              <div class="clean"></div>
            </div>
            
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p9.insertWord("p9")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!--左二右一-->
          <div class="ps2_frmae_p10">
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
                  <input type="file" id="upfile_p10_0" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域1" onFocus="this.value=='文字区域1'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp10_0" class="txtColor" value="#ffffff" readonly style="display:none" />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp10_0" class="bgColor" value="#4C4C4C" readonly style="display:none" />
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
                  <input type="file" id="upfile_p10_1" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域2" onFocus="this.value=='文字区域2'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp10_1" style="display:none" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px; display:none">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp10_1" style="display:none" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p10_2" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域3" onFocus="this.value=='文字区域3'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorWordp10_2" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorBgp10_2" class="bgColor" value="#4C4C4C" readonly />
              <div class="clean"></div>
            </div>
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p10.insertWord("p10")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!--左四右一-->
          <div class="ps2_frmae_p11">
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
                  <input type="file" id="upfile_p11_0" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域1" onFocus="this.value=='文字区域1'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp11_0" class="txtColor" value="#ffffff" readonly style="display:none" />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp11_0" class="bgColor" value="#4C4C4C" readonly style="display:none" />
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
                  <input type="file" id="upfile_p11_1" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域2" onFocus="this.value=='文字区域2'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp11_1" style="display:none" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px; display:none">背景：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp11_1" style="display:none" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p11_2" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域3" onFocus="this.value=='文字区域3'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorWordp11_2" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorBgp11_2" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p11_3" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域4" onFocus="this.value=='文字区域4'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorWordp11_3" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorBgp11_3" class="bgColor" value="#4C4C4C" readonly />
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
                  <input type="file" id="upfile_p11_4" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="文字区域5" onFocus="this.value=='文字区域5'?this.value='':''" />
              </div>
              <div class="colorTitle" style="display:none">颜色：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorWordp11_4" class="txtColor" value="#ffffff" readonly />
              <div class="colorTitle" style="margin-left:20px;display:none">背景：</div>
              <input type="text" maxlength="8" size="8" style="display:none" id="colorSelectorBgp11_4" class="bgColor" value="#4C4C4C" readonly />
              <div class="clean"></div>
            </div>
            
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p11.insertWord("p11")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!--文字-->
          <div class="ps2_frmae_p2"> 
            <div class="editorSprite">
              <textarea name="content" id="content" style="width:100%; height:300px;"></textarea>
            </div>
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p2.insertHtml("p2")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!---图片-->
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
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p3.insertWord("p3")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!---幻灯片-->
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
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p4.insertWord("p4")'>保 存</div>
            <div class="clean"></div>
          </div>
          
          <!---电话拨号-->
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
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p5.insertHtml("p5")'>保 存</div>
            <div class="clean"></div>
          </div>
          
		  <!--搜索框-->
          <div class="ps2_frmae_p12">
            <div class="pNotice">注：搜索按钮(宽：40px，高：30px)</div>
            <div class="clean"></div>
            <div class="warp_packges">
              <div class="selectLink" style="display:none">链接地址：
                <select name='Url'>
                  <?php UrlList(); ?>
                </select>
              </div>
              <div  class="wrap_upload">
                <div class="uploadBtn hand">
                  <input type="file" id="upfile_search" />
                </div>
              </div>
              <div class="clean"></div>
              <div class="img_name" style="display:none">
                <input type="text" value="添加文字" onFocus="this.value=='添加文字'?this.value='':''" />
              </div>
              <div class="colorTitle">区域背景颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorBgp12_0" class="bgColor" value="#" readonly />
              <div class="clean"></div>
              <div class="colorTitle" style="margin-left:20px;">搜索边框颜色：</div>
              <input type="text" maxlength="8" size="8" id="colorSelectorWordp12_0" class="txtColor" value="#" readonly />
              
              <div class="clean"></div>
            </div>
            <div class='btn_green btn_w_120 editBtn hand' onclick='shop_obj.p12.insertWord("p12")'>保 存</div>
            <div class="clean"></div>
          </div>
		  
        </div>
      </div>
  		<!-- 属性end -->
 	 	<div class="clear"></div>
	</div>
  </div>
</div>
</body>
</html>
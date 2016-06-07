<?php
$Dwidth = array('640','428','212','212','216','212','160','160','320');
$DHeight = array('260','218','218','170','170','170','198','198','198');

if(empty($rsSkin['Home_Json'])){
	for($no=1;$no<=9;$no++){
		$json[$no-1]=array(
			"ContentsType"=>$no==1?"1":"0",
			"Title"=>$no==1?json_encode(array("")):"",
			"ImgPath"=>$no==1?json_encode(array("/api/web/skin/".$rsSkin['Skin_ID']."/banner.jpg")):"/api/web/skin/".$rsSkin['Skin_ID']."/i".($no-2).".jpg",
			"Url"=>$no==1?json_encode(array("")):"",
			"Postion"=>"t0".$no,
			"Width"=>$Dwidth[$no-1],
			"Height"=>$DHeight[$no-1],
			"NeedLink"=>"1"
		);
	}
}else{
	$Home_Json=json_decode($rsSkin['Home_Json'],true);
	for($no=1;$no<=9;$no++){
		$json[$no-1]=array(
			"ContentsType"=>$no==1?"1":"0",
			"Title"=>$no==1?json_encode($Home_Json[$no-1]['Title']):$Home_Json[$no-1]['Title'],
			"ImgPath"=>$no==1?json_encode($Home_Json[$no-1]['ImgPath']):$Home_Json[$no-1]['ImgPath'],
			"Url"=>$no==1?json_encode($Home_Json[$no-1]['Url']):$Home_Json[$no-1]['Url'],
			"Postion"=>"t0".$no,
			"Width"=>$Dwidth[$no-1],
			"Height"=>$DHeight[$no-1],
			"NeedLink"=>"1"
		);
	}
}

if($_POST){
	$no=intval($_POST["no"])+1;
	if(empty($_POST["ImgPath"])){
		$_POST["TitleList"]=array();
		foreach($_POST["ImgPathList"] as $key=>$value){
			$_POST["TitleList"][$key]="";
			if(empty($value)){
				unset($_POST["TitleList"][$key]);
				unset($_POST["ImgPathList"][$key]);
				unset($_POST["UrlList"][$key]);
			}
		}
	}
	$Home_Json[$no-1]=array(
		"ContentsType"=>$no==1?"1":"0",
		"Title"=>$no==1?array_merge($_POST["TitleList"]):$_POST['Title'],
		"ImgPath"=>$no==1?array_merge($_POST["ImgPathList"]):$_POST["ImgPath"],
		"Url"=>$no==1?array_merge($_POST["UrlList"]):$_POST['Url'],
		"Postion"=>"t0".$no,
		"Width"=>$Dwidth[$no-1],
		"Height"=>$DHeight[$no-1],
		"NeedLink"=>"1"
	);
	$Data=array(
		"Home_Json"=>json_encode($Home_Json,JSON_UNESCAPED_UNICODE),
	);
	$Flag=$DB->Set("web_home",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Skin_ID=".$rsConfig['Skin_ID']);
	if($Flag){
		$json=array(
			"Title"=>$no==1?json_encode(array_merge($_POST["TitleList"])):$_POST['Title'],
			"ImgPath"=>$no==1?json_encode(array_merge($_POST["ImgPathList"])):$_POST["ImgPath"],
			"Url"=>$no==1?json_encode(array_merge($_POST["UrlList"])):$_POST['Url'],
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
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $SiteName;?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css?t=<?php echo time() ?>' rel='stylesheet' type='text/css' />
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
    <link href='/static/member/css/web.css?t=<?php echo time() ?>' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/web.js?t=<?php echo time() ?>'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="./config.php">基本设置</a></li>
        <li class=""><a href="./skin.php">风格设置</a></li>
        <li class="cur"><a href="./home.php">首页设置</a></li>
        <li class=""><a href="./column.php">栏目管理</a></li>
        <li class=""><a href="./article.php">一键导航</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script> 
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
    <link href='/static/api/web/skin/<?php echo $rsConfig['Skin_ID'];?>/page.css' rel='stylesheet' type='text/css' />
    <script language="javascript">var web_skin_data=<?php echo json_encode($json) ?>;</script>
    <script language="javascript">$(document).ready(web_obj.home_init);</script>
    <div id="home" class="r_con_wrap">
      <div class="m_lefter">
	   <script language="javascript">
		   var skin_index_init=function(){
			   $('#header, #footer, #footer_points, #support').hide();
			   $('#skin_index_menu').css('left', $(window).width()/2-145).show();
			   $('#web_skin_index .banner *').not('img').height($(window).height());
	       }
	   </script>
     <div id="web_skin_index">
          <div class="web_skin_index_list banner" rel="edit-t01">
            <div class="img"><img src="banner.png" /></div>
          </div>
          
    <div class="web_skin_index_list i5" rel="edit-t02">
        <div class="img"><img src="i1.png" /></div>
    </div>
	<div class="web_skin_index_list i6" rel="edit-t03">
        <div class="img"><img src="i2.png" /></div>
     </div>   
          
          <div class="web_skin_index_list i0" rel="edit-t04">
            <div class="img"><img src="i3.png" /></div>
          </div>
          <div class="web_skin_index_list i1" rel="edit-t05">
            <div class="img"><img src="i4.png" /></div>
          </div>
          <div class="web_skin_index_list i0" rel="edit-t06">
            <div class="img"><img src="i5.png" /></div>
          </div>
         
     
            <div class="web_skin_index_list i7" rel="edit-t07">
            <div class="img"><img src="i6.png" /></div>
          </div>
          <div class="web_skin_index_list i8" rel="edit-t08">
            <div class="img"><img src="i7.png" /></div>
          </div>
          <div class="web_skin_index_list i9" rel="edit-t09">
            <div class="img"><img src="i8.png" /></div>
          </div>
        </div>
      </div>
      <div class="m_righter">
        <form id="home_form">
          <div id="setbanner">
            <div class="item">
              <div class="rows">
                <div class="b_l"> <strong>图片(1)</strong><span class="tips">大图建议尺寸：
                  <label></label>
                  px</span><a href="#web_home_img_del" value="0"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a><br />
                  <div class="blank6"></div>
                  <input type="hidden" name="Title[]" value="" />
                  <div>
                    <input name="FileUpload" id="HomeFileUpload_0" type="file" />
                  </div>
                </div>
                <div class="b_r"></div>
                <input type="hidden" name="ImgPathList[]" value="" />
              </div>
              <div class="blank9"></div>
              <div class="rows url_select">
                <div class="u_l">链接页面</div>
                <div class="u_r">
                  <select name='UrlList[]'>
                    <?php UrlList(); ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="rows">
                <div class="b_l"> <strong>图片(2)</strong><span class="tips">大图建议尺寸：
                  <label></label>
                  px</span><a href="#web_home_img_del" value="1"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a><br />
                  <div class="blank6"></div>
                  <input type="hidden" name="Title[]" value="" />
                  <div>
                    <input name="FileUpload" id="HomeFileUpload_1" type="file" />
                  </div>
                </div>
                <div class="b_r"></div>
                <input type="hidden" name="ImgPathList[]" value="" />
              </div>
              <div class="blank9"></div>
              <div class="rows url_select">
                <div class="u_l">链接页面</div>
                <div class="u_r">
                  <select name='UrlList[]'>
                    <?php UrlList(); ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="rows">
                <div class="b_l"> <strong>图片(3)</strong><span class="tips">大图建议尺寸：
                  <label></label>
                  px</span><a href="#web_home_img_del" value="2"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a><br />
                  <div class="blank6"></div>
                  <input type="hidden" name="Title[]" value="" />
                  <div>
                    <input name="FileUpload" id="HomeFileUpload_2" type="file" />
                  </div>
                </div>
                <div class="b_r"></div>
                <input type="hidden" name="ImgPathList[]" value="" />
              </div>
              <div class="blank9"></div>
              <div class="rows url_select">
                <div class="u_l">链接页面</div>
                <div class="u_r">
                  <select name='UrlList[]'>
                    <?php UrlList(); ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="rows">
                <div class="b_l"> <strong>图片(4)</strong><span class="tips">大图建议尺寸：
                  <label></label>
                  px</span><a href="#web_home_img_del" value="3"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a><br />
                  <div class="blank6"></div>
                  <input type="hidden" name="Title[]" value="" />
                  <div>
                    <input name="FileUpload" id="HomeFileUpload_3" type="file" />
                  </div>
                </div>
                <div class="b_r"></div>
                <input type="hidden" name="ImgPathList[]" value="" />
              </div>
              <div class="blank9"></div>
              <div class="rows url_select">
                <div class="u_l">链接页面</div>
                <div class="u_r">
                  <select name='UrlList[]'>
                    <?php UrlList(); ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="rows">
                <div class="b_l"> <strong>图片(5)</strong><span class="tips">大图建议尺寸：
                  <label></label>
                  px</span><a href="#web_home_img_del" value="4"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a><br />
                  <div class="blank6"></div>
                  <input type="hidden" name="Title[]" value="" />
                  <div>
                    <input name="FileUpload" id="HomeFileUpload_4" type="file" />
                  </div>
                </div>
                <div class="b_r"></div>
                <input type="hidden" name="ImgPathList[]" value="" />
              </div>
              <div class="blank9"></div>
              <div class="rows url_select">
                <div class="u_l">链接页面</div>
                <div class="u_r">
                  <select name='UrlList[]'>
                    <?php UrlList(); ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div id="setimages">
            <div class="item">
              <div value="title"> <span class="fc_red">*</span> 标题<br />
                <div class="input">
                  <input name="Title" value="" type="text" />
                </div>
                <div class="blank20"></div>
              </div>
              <div value="images"> <span class="fc_red">*</span> 图片<span class="tips">大图建议尺寸：
                <label></label>
                px</span><br />
                <div class="blank6"></div>
                <div>
                  <input name="FileUpload" id="HomeFileUpload" type="file" />
                </div>
                <div class="blank20"></div>
              </div>
              <div class="url_select"> <span class="fc_red">*</span> 链接页面<br />
                <div class="input">
                  <select name='Url'>
                    <?php UrlList(); ?>
                  </select>
                </div>
              </div>
              <input type="hidden" name="ImgPath" value="" />
            </div>
          </div>
          <div class="button">
            <input type="submit" class="btn_green" name="submit_button" style="cursor:pointer" value="提交保存" />
          </div>
          <input type="hidden" name="PId" value="" />
          <input type="hidden" name="SId" value="" />
          <input type="hidden" name="ContentsType" value="" />
          <input type="hidden" name="no" value="" />
        </form>
      </div>
      <div class="clear"></div>
    </div>
    <div id="home_mod_tips" class="lean-modal pop_win">
      <div class="h">首页设置<a class="modal_close" href="#"></a></div>
      <div class="tips">首页设置成功</div>
    </div>
  </div>
</div>
</body>
</html>
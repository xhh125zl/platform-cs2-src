<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$CategoryID=empty($_REQUEST['CategoryID'])?0:$_REQUEST['CategoryID'];
$rsCategory=$DB->GetRs("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ID=".$CategoryID);
if($_POST)
{
	
	$Data=array(
		"Category_Index"=>$_POST['Index'],
		"Category_Name"=>$_POST["Name"],
		"Category_ParentID"=>$_POST["ParentID"],
		"Category_ListTypeID"=>$_POST["ListTypeID"],
		"Category_Img"=>$_POST['ImgPath'],
                "Category_Bond"=>$_POST["Category_Bond"],
		//"Category_CommissionRate"=>$_POST["Category_CommissionRate"],
		//"Category_ProfitRate"=>$_POST['Category_ProfitRate']
	);
	$Flag=$DB->Set("shop_category",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Category_ID=".$CategoryID);
	if($Flag)
	{
		echo '<script language="javascript">alert("修改成功");window.location="category.php";</script>';
	}else
	{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
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
<script type="text/javascript" src="/third_party/uploadify/jquery.uploadify.min.js"></script>
<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/member/js/shop.js'></script>
<script type='text/javascript'>
$(document).ready(function(){
	shop_obj.category_init();
});
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    
    <div class="r_nav">
      <ul>
        <li class=""><a href="products.php">产品列表</a></li>
        <li class="cur"><a href="category.php">产品分类</a></li>
        <li class=""><a href="commit.php">产品评论</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script>
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <script language="javascript">//$(document).ready(shop_obj.products_category_init);</script>
      <div class="category">
        <div class="m_righter" style="margin-left:0px;">
          <form action="category_edit.php" name="category_form" id="category_form" method="post">
              <input name="CategoryID" type="hidden" value="<?php echo $rsCategory["Category_ID"] ?>">
            <h1>添加产品分类</h1>
             <div class="opt_item">
              <label>菜单排序：</label>
              <span class="input">
              <input type="text" name="Index" value="<?php echo $rsCategory["Category_Index"] ?>" class="form_input" size="5" maxlength="30" notnull />
              <font class="fc_red">*</font>请输入数字</span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label>类别名称：</label>
              <span class="input">
              <input type="text" name="Name" value="<?php echo $rsCategory["Category_Name"] ?>" class="form_input" size="15" maxlength="30" notnull />
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            <div id="cate2" style='display:<?php echo ($rsCategory["Category_ParentID"] == 0)?'none':'';?> '>    
            <!--<div class="opt_item">
							<label>提成比例：</label>
							<span class="input">
							<input type="text" name="Category_CommissionRate" value="<?php echo $rsCategory["Category_CommissionRate"] ?>" class="form_input" size="15" maxlength="30" notnull />%
							<font class="fc_red"></font>网站提成比例</span>
							<div class="clear"></div>
						</div>-->
                                                <div class="opt_item">
							<label>保证金：</label>
							<span class="input">
							<input type="text" name="Category_Bond" value="<?php echo $rsCategory["Category_Bond"] ?>" class="form_input" size="15" maxlength="30" notnull />元
							<font class="fc_red"></font></span>
							<div class="clear"></div>
						</div>
                                 <!--               <div class="opt_item">
							<label>发放比例：</label>
							<span class="input">
							<input type="text" name="Category_ProfitRate" value="<?php echo $rsCategory["Category_ProfitRate"] ?>" class="form_input" size="15" maxlength="30" notnull />%
							<font class="fc_red"></font>佣金发放比例</span>
							<div class="clear"></div>
						</div>-->
             </div>    
                <div class="opt_item">
              <label>隶属关系：</label>
              <span class="input">
              <select name='ParentID' id="changeCate" onChange="changeCates()">
                <option value='0'>─根节点─</option>
<?php $DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ParentID=0 order by Category_Index asc");
while($rsPCategory=$DB->fetch_assoc()){
	echo '<option value="'.$rsPCategory["Category_ID"].'"'.($rsPCategory["Category_ID"]==$rsCategory["Category_ParentID"]?" selected":"").'>&nbsp;├'.$rsPCategory["Category_Name"].'</option>';
}?>
              </select>
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
            	<label>分类图片</label>
                <div class="file">
                    <input id="ImgUpload" name="ImgUpload" type="file">
                    (图片尺寸100px*100px)
                 </div>
                    <br /><br/>
                  <div class="img" id="ImgDetail"><img width="100" src="<?php echo empty($rsCategory["Category_Img"])?"/static/member/images/shop/nopic.jpg":$rsCategory["Category_Img"]; ?>" ></div>
                  <input type="hidden" id="ImgPath" name="ImgPath" value="<?=$rsCategory['Category_Img']?>" />
                   
                <div classs="pro-list-type">
                </div>
                <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label>显示方式：</label>
              
              <ul id="pro-list-type">
                <li>
                  <div id="List0" class="item<?php echo $rsCategory["Category_ListTypeID"]==0?" item_on":""; ?>" ListTypeId="0">
                    <div class="img"><img src="/static/member/images/shop/pro-list-0.jpg" /></div>
                    <div class="filter"></div>
                    <div class="bg" onClick="document.getElementById('List0').className='item item_on';document.getElementById('List1').className='item';document.getElementById('ListTypeID').value=0;"></div>
                  </div>
                </li>
                <li>
                  <div id="List1" class="item<?php echo $rsCategory["Category_ListTypeID"]==1?" item_on":""; ?>" ListTypeId="1">
                    <div class="img"><img src="/static/member/images/shop/pro-list-1.jpg" /></div>
                    <div class="filter"></div>
                    <div class="bg" onClick="document.getElementById('List0').className='item';document.getElementById('List1').className='item item_on';document.getElementById('ListTypeID').value=1;"></div>
                  </div>
                </li>
              </ul>
              <input type="hidden" id="ListTypeID" name="ListTypeID" value="<?php echo $rsCategory["Category_ListTypeID"] ?>">
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="修改分类" />
              <a href="javascript:void(0);" class="btn_gray" onClick="location.href='category.php'">返回</a></span>
              <div class="clear"></div>
            </div>
          </form>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
    <script>
    function changeCates () {
       var changeCate =  $("#changeCate").val();
       if (changeCate == 0) {
           $("#cate2").css('display','none');
       } else {
           
           $("#cate2").css('display','block');
       }
        
    }
    </script>
</body>
</html>
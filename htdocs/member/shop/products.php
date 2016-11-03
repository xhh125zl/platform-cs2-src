<?php  
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
if(isset($_GET['search'])){
	if($_GET['Keyword']){
		$condition .= " and Products_Name like '%".$_GET['Keyword']."%'";
	}
	if($_GET['SearchCateId']>0){
		$catid = $_GET['SearchCateId'];
                $condition .= " and Products_Category like '%,".$catid.",%'";
	}
	if($_GET['Status']>0){
		$condition .= " and Products_Status=".($_GET["Status"]-1);
	}
	if($_GET['BizID']>0){
		$condition .= " and Biz_ID=".$_GET['BizID'];
	}
	if($_GET["Attr"]){
		$condition .= " and Products_".$_GET["Attr"]."=1";
	}
}
$condition .= " order by Products_ID desc";
$biz = $shop_cate = array();
$DB->get("biz","Biz_ID,Biz_Name","where Users_ID='".$_SESSION["Users_ID"]."'");
while($value = $DB->fetch_assoc()){
	$biz[$value["Biz_ID"]] = $value;
}
	
$DB->get("shop_category","Category_ParentID,Category_ID,Category_Name","where Users_ID='".$_SESSION["Users_ID"]."' order by Category_ParentID asc,Category_Index asc");
$shop_cate = array();
while($r=$DB->fetch_assoc()){
	if($r["Category_ParentID"]==0){
		$shop_cate[$r["Category_ID"]] = $r;
	}else{
		$shop_cate[$r["Category_ParentID"]]["child"][] = $r;
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
<script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
</head>
<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="products.php">产品列表</a></li>
        <li class=""><a href="category.php">产品分类</a></li>
        <li class=""><a href="commision_setting.php">佣金设置</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(shop_obj.products_list_init);</script>
      <div class="control_btn">
      <a href="#search" class="btn_green btn_w_120">产品搜索</a> 
      <a href="javascript:void(0);" class="btn_green btn_w_120" id="excoutput">导出产品</a>
      </div>
      <form class="search" method="get" action="products.php" id="search_form">
        关键词：
        <input type="text" name="Keyword" value="<?php echo empty($_GET['Keyword']) ? '' : $_GET['Keyword'];?>" class="form_input" size="15" />&nbsp;
        产品分类：
        <select name='SearchCateId'>
          <option value=''>--请选择--</option>
          <?php
          foreach($shop_cate as $key=>$value){
			  $checked = (!empty($_GET['SearchCateId'])&&($_GET['SearchCateId'] == $value['Category_ID'])) ? 'selected=true' : '';
			  echo '<option value="'.$value["Category_ID"].'" '.$checked.'>'.$value["Category_Name"].'</option>';
			  if(!empty($value["child"])){
				  foreach($value["child"] as $v){
					  echo '<option value="'.$v["Category_ID"].'">└'.$v["Category_Name"].'</option>';
				  }
			  }
		  }
		  ?>
        </select>&nbsp;
        商家：
        <select name='BizID'>
          <option value='0'>--请选择--</option>
          <?php
			foreach($biz as $key=>$value){
				$checked = (!empty($_GET['BizID'])&&($_GET['BizID'] == $value['Biz_ID'])) ? 'selected=true' : '';
		  		echo '<option value="'.$value["Biz_ID"].'" '.$checked.'>'.$value["Biz_Name"].'</option>';
		  	}
		  ?>
        </select>&nbsp;
        其他属性：
        <select name="Attr">
          <option value="0">--请选择--</option>
          <option value="SoldOut">下架</option>
          <option value="IsNew">新品</option>
          <option value="IsHot">热卖</option>
        </select>&nbsp;
        状态：
        <select name="Status">
          <option value="0">全部</option>
          <option value="1">未审核</option>
          <option value="2">已审核</option>
        </select>
		<input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="6%" nowrap="nowrap">序号</td>
            <td width="12%" nowrap="nowrap">名称</td>
            <td width="12%" nowrap="nowrap">所属商家</td>
            <td width="12%" nowrap="nowrap">结算明细</td>
            <td width="8%" nowrap="nowrap">佣金比例</td>
            <td width="8%" nowrap="nowrap">价格</td>
            <td width="8%" nowrap="nowrap">图片</td>
            <td width="8%" nowrap="nowrap">二维码</td>
            <td width="6%" nowrap="nowrap">其他属性</td>
            <td width="6%" nowrap="nowrap">状态</td>
            <td width="8%" nowrap="nowrap">时间</td>
            <td width="6%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php 
		  $lists = array();
		  $DB->getPage("shop_products","*",$condition,10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$rsProducts){
			  $JSON=json_decode($rsProducts['Products_JSON'],true);
		  ?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $rsProducts["Products_ID"] ?></td>
            <td><?php echo $rsProducts["Products_Name"] ?></td>
            <td><?php echo empty($biz[$rsProducts["Biz_ID"]]) ? '' : $biz[$rsProducts["Biz_ID"]]["Biz_Name"];?></td>
            <td style="text-align:left; padding:5px">
            	<?php if($rsProducts["Products_FinanceType"]==0){?>
                结算类型：按交易额比例<br />
                网站提成：<?php echo $rsProducts["Products_PriceX"]?> * <?php echo $rsProducts["Products_FinanceRate"];?> % = <?php echo number_format($rsProducts["Products_PriceX"] * $rsProducts["Products_FinanceRate"]/100,2,'.','');?>
				<?php }else{?>
                结算类型：按产品供货价<br />
                供货价：<?php echo $rsProducts["Products_PriceS"];?><br />
                网站提成：<?php echo $rsProducts["Products_PriceX"]?> - <?php echo $rsProducts["Products_PriceS"];?> = <?php echo $rsProducts["Products_PriceX"]-$rsProducts["Products_PriceS"];?>
                <?php }?>
            	
			</td>
            <td>
			<!--edit in 20160322-->
			<label class="mousehand" id="<?=$rsProducts["Products_ID"]?>">查看详细</label>
            </td>
            <td nowrap="nowrap"><del>￥<?php echo $rsProducts["Products_PriceY"] ?><br>
              </del>￥<?php echo $rsProducts["Products_PriceX"] ?></td>
            <td nowrap="nowrap"><?php echo empty($JSON["ImgPath"])?'':'<img src="'.$JSON["ImgPath"][0].'" class="proimg" />'; ?></td>
            <td nowraqp="nowrap">
            <img width="80" height="80" src="<?=$rsProducts['Products_Qrcode']?>" /></td>
            <td nowrap="nowrap"><?php echo empty($rsProducts["Products_SoldOut"])?"":"下架<br>";
			echo empty($rsProducts["Products_IsShippingFree"])?"":"免运费<br>";
			echo empty($rsProducts["Products_IsNew"])?"":"新品<br>";
			echo empty($rsProducts["Products_IsRecommend"])?"":"推荐<br>";
			echo empty($rsProducts["Products_IsHot"])?"":"热卖"; ?></td>
         	<td nowrap="nowrap"><?php echo $rsProducts["Products_Status"]==0 ? '<font style="color:red">未审核</font>' : '<font style="color:blue">已审核</font>'; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d",$rsProducts["Products_CreateTime"]) ?></td>
            <td class="last" nowrap="nowrap"><a href="products_edit.php?ProductsID=<?php echo $rsProducts["Products_ID"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a>
			<!--
			<a href="products.php?action=del&ProductsID=<?php echo $rsProducts["Products_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a>-->
			</td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
	$('#excoutput').click(function(){
		window.location = './output.php?' + $('#search_form').serialize() + '&type=product_gross_info';
	})
});
    
</script>
</body>
</html>
<?php  
if (isset($_GET["action"])) {
    if ($_GET["action"] == "del") {
        $Flag = $DB->Del("cloud_products", "Users_ID='{$UsersID}' and Products_ID=" . $_GET["ProductsID"]);
        if ($Flag) {
            echo '<script language="javascript">alert("删除成功");window.location="' . $_SERVER['HTTP_REFERER'] . '";</script>';
        } else {
            echo '<script language="javascript">alert("删除失败");history.back();</script>';
        }
        exit();
    }
}

$condition = "where Users_ID='{$UsersID}' AND Biz_ID={$BizID}";
if (isset($_GET['search'])) {
    setcookie("{$UsersID}_SearchIsShow", 1);
    if ($_GET['Keyword']) {
        setcookie("{$UsersID}_Keyword", $_GET['Keyword']);
        $condition .= " and Products_Name like '%" . trim($_GET['Keyword']) . "%'";
    }
    if ($_GET['SearchCateId']) {
        setcookie("{$UsersID}_SearchCateId", $_GET['SearchCateId']);
        $condition .= " and Products_Category=" . $_GET['SearchCateId'];
    }
    if ($_GET["Attr"]) {
        setcookie("{$UsersID}_Attr", $_GET['Attr']);
        $condition .= " and Products_" . $_GET["Attr"] . "=1";
    }
}
$condition .= " order by Products_ID desc";

function get_category($catid)
{
    global $DB;
    $r = $DB->GetRs("cloud_category", "*", "where Category_ID='" . $catid . "'");
    return $r['Category_Name'];
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微易宝</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <?php include "top.php"; ?>
    <div id="products" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(shop_obj.products_list_init);</script>
      <div class="control_btn">
      <a href="products_add.php" class="btn_green btn_w_120">添加产品</a> <a href="#search" class="btn_green btn_w_120">产品搜索</a> 
      </div>
      <form class="search" method="get" action="products.php"  >
        关键词：
        <input type="text" name="Keyword" value="" class="form_input" size="15" />
        产品分类：
        <select name='SearchCateId'>
          <option value=''>--请选择--</option>
          <?php
        $DB->get("cloud_category", "*", "where Users_ID='{$UsersID}' and Category_ParentID=0 order by Category_Index asc");
        $ParentCategory = array();
        $i = 1;
        while ($rsPCategory = $DB->fetch_assoc()) {
            $ParentCategory[$i] = $rsPCategory;
            $i ++;
        }
        foreach ($ParentCategory as $key => $value) {
            $DB->get("cloud_category", "*", "where Users_ID='{$UsersID}' and Category_ParentID=" . $value["Category_ID"] . " order by Category_Index asc");
            if ($DB->num_rows() > 0) {
                echo '<optgroup label="' . $value["Category_Name"] . '">';
                while ($rsCategory = $DB->fetch_assoc()) {
                    if ($_COOKIE["{$UsersID}_SearchCateId"] && $_COOKIE["{$UsersID}_SearchCateId"] === $rsCategory["Category_ID"]) {
                        echo '<option value="' . $rsCategory["Category_ID"] . '" selected>' . $rsCategory["Category_Name"] . '</option>';
                    } else {
                        echo '<option value="' . $rsCategory["Category_ID"] . '">' . $rsCategory["Category_Name"] . '</option>';
                    }
                }
                echo '</optgroup>';
            } else {
                if (isset($_COOKIE["{$UsersID}_SearchCateId"]) && $_COOKIE["{$UsersID}_SearchCateId"] && $_COOKIE["{$UsersID}_SearchCateId"] === $value["Category_ID"]) {
                    echo '<option value="' . $value["Category_ID"] . '" selected>' . $value["Category_Name"] . '</option>';
                } else {
                    echo '<option value="' . $value["Category_ID"] . '">' . $value["Category_Name"] . '</option>';
                }
            }
        }
        ?>
        </select>
        其他属性：
        <select name="Attr">
          <option value="0">--请选择--</option>
          <option value="SoldOut" <?=isset($_COOKIE["{$UsersID}_Attr"]) && $_COOKIE["{$UsersID}_Attr"]=="SoldOut"?"selected":"" ?>>下架</option>
          <option value="IsNew" <?=isset($_COOKIE["{$UsersID}_Attr"]) && $_COOKIE["{$UsersID}_Attr"]=="IsNew"?"selected":"" ?>>新品</option>
          <option value="IsHot" <?=isset($_COOKIE["{$UsersID}_Attr"]) && $_COOKIE["{$UsersID}_Attr"]=="IsHot"?"selected":"" ?>>热卖</option>
        </select>
		<input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
	  <style>
		.tips_info {
			background: #f7f7f7 none repeat scroll 0 0;
			border: 1px solid #ddd;
			border-radius: 5px;
			font-size: 12px;
			line-height: 22px;
			margin-bottom: 10px;
			padding: 10px;
		}
	  </style>
	  <div class="tips_info">
	    注：<font style="color:#F00; font-size:12px;">        点击查看往期可以查看该商品的所有期数商品！ </font><br />
	  </div>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="16%" nowrap="nowrap">名称</td>
            <td width="8%" nowrap="nowrap" style="display:none;">分销佣金</td>
            <td width="8%" nowrap="nowrap" style="display:none;">佣金/爵位比</td>
            <td width="8%" nowrap="nowrap">属性分类</td>
            <td width="15%" nowrap="nowrap">价格</td>
            <td width="10%" nowrap="nowrap">图片</td>
            <td width="16%" nowrap="nowrap">二维码</td>
            <td width="6%" nowrap="nowrap">其他属性</td>
            <td width="5%" nowrap="nowrap">期数</td>
            <td width="5%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap">时间</td>
            <td width="17%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php 
		  $lists = array();
		  $DB->getPage("cloud_products","*",$condition,10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$rsProducts){
			  $JSON=json_decode($rsProducts['Products_JSON'],true);
			  ?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $rsProducts["Products_ID"] ?></td>
            <td><?php echo $rsProducts["Products_Name"] ?></td>
            <td style="display:none;">
			
			<?php 
				/*$shop_config = shop_config($_SESSION["Users_ID"]);*/
				$distribute_list = json_decode($rsProducts["Products_Distributes"],true);
				$shop_config = dis_config($UsersID); 
				$arr = array('一','二','三','四','五','六','七','八','九','十');
				$level =  $shop_config['Dis_Self_Bonus']?$shop_config['Dis_Level']+1:$shop_config['Dis_Level'];
				for($i=0;$i<$level;$i++){?>
				<?php echo $arr[$i];?>级&nbsp;&nbsp;%<?=!empty($distribute_list[$i])?$distribute_list[$i]:0?><br/>
				<?php }?>
            </td>
            <td style="display:none;">
            <?php echo $rsProducts["commission_ratio"].'/'.(100-$rsProducts["commission_ratio"]);?>
			</td>
			<td>
            <?php echo get_category($rsProducts["Products_Category"]);?>
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
            <td nowrap="nowrap">第&nbsp;<b><?php echo $rsProducts["qishu"] ?></b>&nbsp;期</td>
            <td nowrap="nowrap"><?php echo $rsProducts["Products_Status"]==0 ? '<font style="color:red">未审核</font>' : '<font style="color:blue">已审核</font>'; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d",$rsProducts["Products_CreateTime"]) ?></td>
            <td class="last" nowrap="nowrap">
			<?php if(empty($rsProducts["canyurenshu"])){?>
			    <a href="products_edit.php?ProductsID=<?php echo $rsProducts["Products_ID"] ?>">[修改]</a>
			<?php }?>
			<a href="?action=del&ProductsID=<?php echo $rsProducts["Products_ID"] ?>">删除</a>
           		<a href="products_detail_list.php?ProductsID=<?php echo $rsProducts["Products_ID"] ?>">查看往期</a>
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
</body>
</html>
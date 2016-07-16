<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/biz/global.php');
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if(isset($_GET["action"])){
	if($_GET["action"]=="del"){

    $Flag=$DB->Del("pintuan_products","Users_ID='".$_SESSION["Users_ID"]."' AND Biz_ID={$_SESSION['BIZ_ID']}  and Products_ID=".$_GET["PintuanID"]);

    if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}
//搜索
$Pt = $DB->Get('pintuan_products','Products_Name','people_num');
$condition = "where Users_ID='".$_SESSION["Users_ID"]."' AND Biz_ID={$_SESSION['BIZ_ID']}";
if (isset($_GET['search']) || isset($_GET['people_num'])){
  if (isset($_GET['pintuan_name']) && $_GET['pintuan_name']){
    $condition .= " and products_name like '%".$_GET['pintuan_name']."%' ORDER BY Products_ID desc";
  }else{
    $condition .= " and people_num=".(int)$_GET["people_num"]." ORDER BY Products_ID desc";
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
        <li class="cur"><a href="./products.php">产品管理</a></li>
	    <li><a href="./orders.php">订单管理</a></li>
        <li><a href="./comment.php">评论管理</a></li>
        <li><a href="./virtual_card.php">虚拟卡密管理</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(shop_obj.products_list_init);</script>
      <div class="control_btn">
      <a href="product_add.php" class="btn_green btn_w_120">添加拼团商品</a> <a href="#search" class="btn_green btn_w_120">商品搜索</a>
      </div>

      <!-- 搜索 -->
      <form class="search" method="get" action="products.php">
        商品名称：
        <input type="text" name="pintuan_name" value="" class="form_input" size="15" />&nbsp;
        拼团人数
        <input type="text" name="people_num" value="" class="form_input" size="15">
		<input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <table align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="48px" align="center"><strong>活动ID</strong></td>
            <td align="center" width="66px"><strong>商品名称</strong></td>
            <td align="center" width="66px"><strong>商品库存</strong></td>
            <td align="center" width="66px"><strong>结算详情</strong></td>
            <td align="center" width="66px"><strong>价格</strong></td>
            <td width="96px" align="center"><strong>是否支持单购</strong></td>
            <td width="96px" align="center"><strong>是否支持抽奖</strong></td>
            <td width="66px" align="center"><strong>拼团人数</strong></td>
            <td width="96px" align="center"><strong>活动时间</strong></td>
            <td width="96px" align="center"><strong>销量</strong></td>
            <td width="50px" align="center"><strong>状态</strong></td>
            <td width="70px" align="center"><strong>操作</strong></td>
          </tr>
          </tr>
        </thead>
        <tbody>
    <?php 
		  $lists = array();
		  $DB->getPage("pintuan_products","*",$condition,10);
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$pintuan){
			  ?>      
          <tr>
            <td nowrap="nowrap" class="id"><?php echo $pintuan["Products_ID"]; ?></td>
            <td nowrap="nowrap"><?php echo $pintuan["Products_Name"]; ?></td>
            <td nowrap="nowrap"><?php echo $pintuan["Products_Count"]; ?></td>
            <td nowrap="nowrap">
            	<?php if($pintuan["Products_FinanceType"]==0){?>
                            结算类型：按交易额比例<br />
                            网站提成：<br/>
                    	<?php echo $pintuan["Products_PriceD"]?> * <?php echo $pintuan["Products_FinanceRate"];?> % = <?php echo number_format($pintuan["Products_PriceD"] * $pintuan["Products_FinanceRate"]/100,2,'.','');?>（单购）<br/>
                    	<?php echo $pintuan["Products_PriceT"]?> * <?php echo $pintuan["Products_FinanceRate"];?> % = <?php echo number_format($pintuan["Products_PriceT"] * $pintuan["Products_FinanceRate"]/100,2,'.','');?>（团购）
            	<?php }else{?>
                            结算类型：按产品供货价<br />
                            供货价：<?php echo $pintuan["Products_PriceSt"];?><br />
                            网站提成：<br/>
                        <?php echo $pintuan["Products_PriceD"]?> - <?php echo $pintuan["Products_PriceSd"];?> = <?php echo $pintuan["Products_PriceD"]-$pintuan["Products_PriceSd"];?>（单购）<br/>
                        <?php echo $pintuan["Products_PriceT"]?> - <?php echo $pintuan["Products_PriceSt"];?> = <?php echo $pintuan["Products_PriceT"]-$pintuan["Products_PriceSt"];?>（团购）<br/>
                <?php }?>
            
            </td>
            <td nowrap="nowrap">单购：<?php echo $pintuan["Products_PriceD"]; ?><br/>团购：<?php echo $pintuan["Products_PriceT"]; ?></td>
            <td nowrap="nowrap"><?php if($pintuan["is_buy"]==1){ echo "是";
            }else{ echo "否";};?></td>
             <td nowrap="nowrap"><?php if($pintuan["Is_Draw"]==1){ echo "不支持";
            }else{ echo "支持";};?></td>
            <td nowrap="nowrap"><?php echo $pintuan["people_num"]; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d",$pintuan["starttime"]); ?> 至 <?php echo date("Y-m-d",$pintuan["stoptime"]); ?></td>
            <td nowrap="nowrap"><?php echo $pintuan["Products_Sales"]?></td>
            <td nowrap="nowrap"><?php echo $pintuan["Products_Status"]==0 ? '<font style="color:red">未审核</font>' : '<font style="color:blue">已审核</font>'; ?></td>
            <td class="last" nowrap="nowrap"><a href="product_edit.php?id=<?php echo $pintuan["Products_ID"]; ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a>&nbsp;&nbsp;
            <a class="onclik"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a>
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
          //设置删除的ajax
      $('.onclik').click(function(){
        //获取服务id
        var id=$(this).parents().find('.id').html();
        if(confirm('您确定删除此服务')){   
          //发送ajax
          $.get("products.php",{PintuanID:id,action:'del'},function(data){
            }, 'json');
          $(this).parents('tr').remove();
        }   
      })
});
    
</script>
</body>
</html>
<?php  
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$condition = "WHERE `Users_ID` = '{$UsersID}' AND Biz_ID={$BizID} AND Products_Status=1";

if (isset($_GET['search'])) {
  if($_GET['Products_Name']){
    $condition .= " and Products_Name like '%".$_GET['Products_Name']."%'";
  }
}
$condition .= " ORDER BY Products_ID DESC";
$List = Array();

//获取活动配置
$active_id = isset($_GET['activeid'])?$_GET['activeid']:0;
$rsActive = $DB->GetRs("active","*","WHERE Users_ID='{$UsersID}' AND Active_ID='{$active_id}'");
if(empty($rsActive)){
    sendAlert("没有要参加的活动");
}
if($rsActive['Type_ID']==0){    //拼团
    $table = "pintuan_products";    
}elseif($rsActive['Type_ID']==1){   //云购
    $table = "cloud_products";
}else{
    $table = "shop_products";
}
$result = $DB->getPage($table,"*",$condition);
$List = $DB->toArray($result);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>选择要推荐的商品</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<style type="text/css">
	input[type='checkbox']{
	   width:20px;height:20px;
	}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div id="products" class="r_con_wrap"> 
      <form class="search" method="get" action="?" style="display:block">
       	 商品名：
        <input type="text" name="Products_Name" value="" class="form_input" size="15" />&nbsp;
        <input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form> 
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">全选<input type="checkbox" id="chose" class="listNum" value="" ></td>
            <td width="8%" nowrap="nowrap">商品名</td>
            <?php if($rsActive['Type_ID']==0){ ?>
            <td width="8%" nowrap="nowrap">库存</td>
            <td width="8%" nowrap="nowrap">价格</td>
            <td width="8%" nowrap="nowrap">销量</td>
            <?php }else{ ?>
            <td width="8%" nowrap="nowrap">价格</td>
            <td width="8%" nowrap="nowrap">重量</td>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
    	<?php
	    	
	    	foreach ($List as $k => $v) {
        ?>
	        <tr>
	          <td nowrap="nowrap" vkname="<?=$v['Products_Name'] ?>">
	          	<input type="checkbox" name="select[]" id="n<?=$v['Products_ID'] ?>" class="listNum<?=$v['Products_ID'] ?>" value="<?=$v['Products_ID'] ?>" >
	          </td>
	          <td><?=$v['Products_Name'] ?></td>
	          <?php if($rsActive['Type_ID']==0){ ?>
	          <td><?=$v['Products_Count'] ?></td>
	          <td>单购：<?=$v["Products_PriceD"]?><br/>
                                    团购：<?=$v["Products_PriceT"]?></td>
	          <td nowrap="nowrap"><?=$v["Products_Sales"]?></td>
	          <?php }else{ ?>
	          <td>商品总价：<?=$v["Products_PriceY"]?><br/>
                                    云购单次价格：<?=$v["Products_PriceX"]?></td>
	          <td nowrap="nowrap"><?=$v["Products_Weight"]?></td>
	          <?php } ?>
	        </tr>

	    <?php } ?>
        </tbody>
      </table>

      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
	    <div id="actionBox">
            <button class="btn_green" style="float:right;margin-right:40%;" href="javascript:void(0);" id="addInsert">插入</button>
      	</div>
    </div>
  </div>
</div>
<script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
<script>
$(document).ready(function(){
  var toplistArr = $('input[name="toplist"]', parent.document).val();
  toplistArr = toplistArr.split(',');
  var store = [],active_count=<?=$rsActive['BizGoodsCount'] ?>,count=1;
  if(toplistArr.length>0){
      for(var i=0;i<toplistArr.length;i++)
      {
          $("#n"+toplistArr[i]).attr("checked","checked");
      }
  }
  $("#chose").click(function(){
      if($(this).prop("checked")==true){
          $("input[name='select[]']").attr("checked","checked");
          var len = $("input[name='select[]']:checked").length;
          if(len>active_count){
              alert("最多允许选择"+active_count+"个");
              $("input[name='select[]']").removeAttr("checked");
              return false;
          }
          $("input[name='select[]']:checked").each(function(){
              store.push({
                id:$(this).val(),
                name:$(this).parent().attr("vkname")
              });
              
          })
          
      }else{
          $("input[name='select[]']").removeAttr("checked");
      }
  });



	
	$("input[name='select[]']").click(function(){
		if($(this).is(":checked")==true){
			if(count>active_count){
				alert("最多允许选择"+active_count+"个");
				$(this).prop("checked",false);
				return ;
			}
			store.push({
				id:$(this).val(),
				name:$(this).parent().attr("vkname")
			});
			count++;
		}
	});
	
	$('#addInsert').click(function(){
		var str = "",toplist="";
		if(store.length>0){
			for(var i=0;i<store.length;i++){
				str+="<option value='"+store[i].id+"'>"+store[i].name+" </option>";
				if(i==store.length-1){
					toplist += store[i].id;
				}else{
					toplist += store[i].id+',';
				}
				
			}
		}

      $('select[name="commit"]', parent.document).append(str);
      $('input[name="toplist"]', parent.document).val(toplist);
      var index = parent.layer.getFrameIndex(window.name);
      parent.layer.close(index);
	});
});
</script>
</body>
</html>
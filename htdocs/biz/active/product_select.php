<?php  
//获取活动配置
$active_id = isset($_GET['activeid'])?$_GET['activeid']:0;
$time = time();
$sql = "SELECT * FROM active AS a LEFT JOIN active_type AS t ON a.Type_ID = t.Type_ID WHERE a.Users_ID='{$UsersID}' AND a.Active_ID='{$active_id}' AND a.Status=1";

$res = $DB->query($sql);
$rsActive = $DB->fetch_array($res);
if(empty($rsActive)){
    sendAlert("没有要参加的活动");
}
$condition = "WHERE ";
$List = Array();

$searchKey = "Products_Name";
if($rsActive['module']=='pintuan'){    //拼团
        $table = "pintuan_products";
        $time = time();
        $condition .= " `Users_ID` = '{$UsersID}' AND Biz_ID={$BizID} AND Products_Status=1  AND starttime<={$time} AND stoptime>={$time}";
}elseif($rsActive['module']=='cloud'){   //云购
        $table = "cloud_products";
        $condition .= " `Users_ID` = '{$UsersID}' AND Biz_ID={$BizID} AND Products_Status=1 AND Products_SoldOut=0";
}elseif($rsActive['module']=='pifa'){   //批发
        $table = "pifa_products";
        $condition .= " `Users_ID` = '{$UsersID}' AND Biz_ID={$BizID} AND Products_Status=1 AND Products_SoldOut=0";
}elseif($rsActive['module']=='zhongchou'){  //重筹
        $table = "zhongchou_project";
        $time = time();
        $condition .= " usersid='{$UsersID}' AND Biz_ID={$BizID} AND fromtime<={$time} AND totime>={$time}";
        $searchKey = "title";
}else{
        $table = "shop_products";
        $condition .= " `Users_ID` = '{$UsersID}' AND Biz_ID={$BizID} AND Products_Status=1 AND Products_SoldOut=0";
}

if (isset($_GET['search'])) {
    if($_GET['keyword']){
        $condition .= " and {$searchKey} like '%".$_GET['keyword']."%'";
    }
}

$result = $DB->getPage($table,"*",$condition);
$List = $DB->toArray($result);
// 获取参加活动的商品数量
$res = $DB->Get("biz_active","*","WHERE Users_ID='{$UsersID}' AND Active_ID={$active_id}");
$glist = $DB->toArray($res);
$goodscount = 0;
if(!empty($glist)){
    $goodsid = '';
    $goodsidlist = [];
    foreach($glist as $k => $v)
    {
        if($v['ListConfig'] && $v['IndexConfig']){
            $goodsid .= $v['ListConfig'].','.$v['IndexConfig'];
        }
    }
    if($goodsid){
        $goodsid = trim($goodsid,',');
        $goodsidlist = explode(',',$goodsid);
        $goodsidlist = array_unique($goodsidlist);
        $goodsidlist = implode(',',$goodsidlist);  
    }

    $res = $DB->GetRs($table,"count(*) as total",$condition.($rsActive['module']=='zhongchou'?" AND itemid IN ($goodsidlist)":" AND Products_ID IN ($goodsidlist)"));
    $goodscount = !empty($res)?$res['total']:0;
}
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
       	 关键词：
        <input type="text" name="keyword" value="" class="form_input" size="15" />&nbsp;
        <input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form> 
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">全选<input type="checkbox" id="chose" class="listNum" value="" ></td>
            <?php if($rsActive['module']=='pintuan'){ ?>
            <td width="8%" nowrap="nowrap">商品名</td>
            <td width="8%" nowrap="nowrap">库存</td>
            <td width="8%" nowrap="nowrap">价格</td>
            <td width="8%" nowrap="nowrap">销量</td>
            <?php }else if($rsActive['module']=='zhongchou'){ ?>
            <td width="8%" nowrap="nowrap">项目名</td>
            <td width="8%" nowrap="nowrap">金额</td>
            <td width="8%" nowrap="nowrap">期限</td>
            <?php }else{ ?>
            <td width="8%" nowrap="nowrap">商品名</td>
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
	          <?php if($rsActive['module']=='pintuan'){ ?>
	          <td nowrap="nowrap" vkname="<?=$v['Products_Name'] ?>">
	          	<input type="checkbox" name="select[]" id="n<?=$v['Products_ID'] ?>" class="listNum<?=$v['Products_ID'] ?>" value="<?=$v['Products_ID'] ?>" >
	          </td>
	          <td><?=$v['Products_Name'] ?></td>
	          <td><?=isset($v['Products_Count'])?$v['Products_Count']:0 ?></td>
	          <td>单购：<?=isset($v["Products_PriceD"])?$v["Products_PriceD"]:0 ?><br/>
                团购：<?=isset($v["Products_PriceT"])?$v["Products_PriceT"]:0 ?></td>
	          <td nowrap="nowrap"><?=isset($v["Products_Sales"])?$v["Products_Sales"]:0 ?></td>
	          <?php }else if($rsActive['module']=='zhongchou'){ ?>
	          <td nowrap="nowrap" vkname="<?=$v['title'] ?>">
	          	<input type="checkbox" name="select[]" id="n<?=$v['itemid'] ?>" class="listNum<?=$v['itemid'] ?>" value="<?=$v['itemid'] ?>" >
	          </td>
	          <td><?=$v['title'] ?></td>
	          <td><?=isset($v['amount'])?$v['amount']:0 ?></td>
	          <td nowrap="nowrap"><?=isset($v["fromtime"])?date("Y-m-d H:i",$v["fromtime"]):date("Y-m-d H:i") ?>-<?=isset($v["totime"])?date("Y-m-d H:i",$v["totime"]):date("Y-m-d H:i") ?></td>
	          <?php }else{ ?>
	          <td nowrap="nowrap" vkname="<?=$v['Products_Name'] ?>">
	          	<input type="checkbox" name="select[]" id="n<?=$v['Products_ID'] ?>" class="listNum<?=$v['Products_ID'] ?>" value="<?=$v['Products_ID'] ?>" >
	          </td>
	          <td><?=$v['Products_Name'] ?></td>
	          <td>商品总价：<?=isset($v["Products_PriceY"])?$v["Products_PriceY"]:0 ?><br/>
                云购单次价格：<?=isset($v["Products_PriceX"])?$v["Products_PriceX"]:0 ?></td>
	          <td nowrap="nowrap"><?=isset($v["Products_Weight"])?$v["Products_Weight"]:0 ?></td>
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
    //推荐首页
  <?php if(isset($_GET['isIndex']) && $_GET['isIndex']==1){  ?>
  var toplistArr = $('input[name="Indexlist"]', parent.document).val();
  var goodscount = <?=$goodscount?$goodscount:0 ?>;
  var allowgoodscount = <?=$rsActive['MaxGoodsCount']?$rsActive['MaxGoodsCount']:0 ?>;
  toplistArr = toplistArr.split(',');
  var store = [],active_count=<?=$rsActive['IndexBizGoodsCount'] ?>,count=1;
  if(toplistArr.length>0){
      for(var i=0;i<toplistArr.length;i++)
      {
          $("#n"+toplistArr[i]).attr("checked","checked");
      }
  }
  //全选
  $("#chose").click(function(){
      if($(this).prop("checked")==true){
          $("input[name='select[]']").attr("checked","checked");
          var len = $("input[name='select[]']:checked").length;
          if(len>active_count){
              alert("最多允许选择"+active_count+"个");
              $("input[name='select[]']").removeAttr("checked");
              return false;
          }
          if(goodscount+len>allowgoodscount){
              alert("最多允许参与的商品数量"+allowgoodscount+"个");
              $("input[name='select[]']").removeAttr("checked");
              return false;
          } 
      }else{
          $("input[name='select[]']").removeAttr("checked");
      }
  });
	$("input[name='select[]']").click(function(){
		if($(this).prop("checked")==true){
		  var len = $("input[name='select[]']:checked").length;
			if(len>active_count){
				alert("最多允许选择"+active_count+"个");
				$(this).prop("checked",false);
				return ;
			}
			if(goodscount+len>allowgoodscount){
            alert("最多允许参与的商品数量"+allowgoodscount+"个");
            $(this).prop("checked",false);
            return ;
      }
		}
	});
	
	$('#addInsert').click(function(){
		var str = "",Indexlist="";
		$("input[name='select[]']:checked").each(function(){
              store.push({
                id:$(this).val(),
                name:$(this).parent().attr("vkname")
              });
              
    });
    if(goodscount+store.length>allowgoodscount){
            alert("最多允许参与的商品数量"+allowgoodscount+"个");
            $("input[name='select[]']").removeAttr("checked");
            return ;
    }
      if(store.length>0){
        for(var i=0;i<store.length;i++){
          str+="<option value='"+store[i].id+"'>"+store[i].name+" </option>";
          if(i==store.length-1){
            Indexlist += store[i].id;
          }else{
            Indexlist += store[i].id+',';
          }
          
        }
      }
      $('select[name="Indexcommit"]', parent.document).text("");
      $('select[name="Indexcommit"]', parent.document).append(str);
      $('input[name="Indexlist"]', parent.document).val(Indexlist);
      var index = parent.layer.getFrameIndex(window.name);
      parent.layer.close(index);
	});
  <?php }else{ ?>
  var toplistArr = $('input[name="toplist"]', parent.document).val();
  toplistArr = toplistArr.split(',');
  var store = [],active_count=<?=$rsActive['BizGoodsCount'] ?>,count=1;
  var goodscount = <?=$goodscount?$goodscount:0 ?>;
  var allowgoodscount = <?=$rsActive['MaxGoodsCount']?$rsActive['MaxGoodsCount']:0 ?>;
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
          if(goodscount+len>allowgoodscount){
              alert("最多允许参与的商品数量"+allowgoodscount+"个");
              $("input[name='select[]']").removeAttr("checked");
              return false;
          }
      }else{
          $("input[name='select[]']").removeAttr("checked");
      }
  });



	
	$("input[name='select[]']").click(function(){
		if($(this).prop("checked")==true){
		  var len = $("input[name='select[]']:checked").length;
			if(len>active_count){
				alert("最多允许选择"+active_count+"个");
				$(this).prop("checked",false);
				return ;
			}
			if(goodscount+len>allowgoodscount){
            alert("最多允许参与的商品数量"+allowgoodscount+"个");
            $(this).prop("checked",false);
            return ;
      }
		}
	});
	
	$('#addInsert').click(function(){
		var str = "",toplist="";
		$("input[name='select[]']:checked").each(function(){
              store.push({
                id:$(this).val(),
                name:$(this).parent().attr("vkname")
              });
              
    });
    if(goodscount+store.length>allowgoodscount){
            alert("最多允许参与的商品数量"+allowgoodscount+"个");
            $("input[name='select[]']").removeAttr("checked");
            return ;
    }
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

      $('select[name="commit"]', parent.document).text("");
      $('select[name="commit"]', parent.document).append(str);
      $('input[name="toplist"]', parent.document).val(toplist);
 
      var index = parent.layer.getFrameIndex(window.name);
      parent.layer.close(index);
	});
	
	<?php } ?>
});
</script>
</body>
</html>
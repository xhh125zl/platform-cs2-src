<?php
$Dwidth = array('640');
$DHeight = array('262');
$Home_Json=json_decode($rsSkin['Home_Json'],true);
for($no=1;$no<=1;$no++){
	$json[$no-1]=array(
		"ContentsType"=>$no==1?"1":"0",
		"Title"=>$no==1?json_encode($Home_Json[$no-1]['Title']):$Home_Json[$no-1]['Title'],
		"ImgPath"=>$no==1?json_encode($Home_Json[$no-1]['ImgPath']):$Home_Json[$no-1]['ImgPath'],
		"Url"=>$no==1?json_encode($Home_Json[$no-1]['Url']):$Home_Json[$no-1]['Url'],
		"Postion"=>"t0".$no,
		"Width"=>$Dwidth[$no-1],
		"Height"=>$DHeight[$no-1],
		"NeedLink"=>1
	);
}

$rsCategory = $DB->get("shop_category","Category_Name,Category_ID,Category_Img","where Users_ID='".$UsersID."' and Category_IndexShow=1 and Category_ParentID=0 order by Category_Index asc ");
$category_list = $DB->toArray($rsCategory);

if(!empty($_POST)){
	if(empty($_POST['cate_id'])){
		//获取新品
		$counts = $DB->GetRs("shop_products","count(Products_ID) as count","where Users_ID='".$UsersID."' and Products_IsNew=1 and Products_SoldOut=0 and Products_Status=1");
		$num = 4;//每页记录数
		$p = !empty($_POST['p'])?intval(trim($_POST['p'])):1;
		$total = $counts['count'];//数据记录总数
		$totalpage = ceil($total/$num);//总计页数
		$limitpage = ($p-1)*$num;//每次查询取记录
		$rsNewProducts = $DB->get("shop_products","Products_Name,Products_ID,Products_JSON,Products_PriceX,Products_Count,Products_Sales","where Users_ID='".$UsersID."' and Products_IsNew=1 and Products_SoldOut=0 and Products_Status=1 order by Products_Index asc,Products_ID desc limit $limitpage,$num");
		$new_products = handle_product_list($DB->toArray($rsNewProducts));
		foreach($new_products as $k => $item){
			$new_products[$k]['link'] = $shop_url.'products/'.$item['Products_ID'].'/';
		}
		if(count($new_products)>0){
			$data = array(
			    'list' => $new_products,
				'totalpage' => $totalpage,
			);
		}else{
			$data = array(//没有数据可加载
			    'list' => '',
				'totalpage' => $totalpage,
			);
		}
		echo json_encode($data);
	}elseif($_POST['cate_id'] == 1){
		//获取推荐商品
		$counts = $DB->GetRs("shop_products","count(Products_ID) as count","where Users_ID='".$UsersID."' and Products_IsRecommend=1 and Products_SoldOut=0 and Products_Status=1");
		$num = 4;//每页记录数
		$p = !empty($_POST['p'])?intval(trim($_POST['p'])):1;
		$total = $counts['count'];//数据记录总数
		$totalpage = ceil($total/$num);//总计页数
		$limitpage = ($p-1)*$num;//每次查询取记录
		$rsRecommendProducts = $DB->get("shop_products","Products_Name,Products_ID,Products_JSON,Products_PriceX,Products_Count,Products_Sales","where Users_ID='".$UsersID."' and Products_IsRecommend=1 and Products_SoldOut=0 and Products_Status=1 order by Products_Index asc,Products_ID desc limit $limitpage,$num");
		$rsRecommendProducts = handle_product_list($DB->toArray($rsRecommendProducts));
		foreach($rsRecommendProducts as $k => $item){
			$rsRecommendProducts[$k]['link'] = $shop_url.'products/'.$item['Products_ID'].'/';
		}
		if(count($rsRecommendProducts)>0){
			$data = array(
			    'list' => $rsRecommendProducts,
				'totalpage' => $totalpage,
			);
		}else{
			$data = array(//没有数据可加载
			    'list' => '',
				'totalpage' => $totalpage,
			);
		}
		echo json_encode($data);
	}
	exit;
}
?>
<?php require_once('skin/top.php');?> 
<body>
<style type="text/css" media="all">
    /**
	 *
	 * 加载更多样式
	 *
	 */
	.pullUp {
		background:#fff;
		height:40px;
		line-height:40px;
		padding:5px 10px;
		border-bottom:1px solid #ccc;
		font-weight:bold;
		font-size:14px;
		color:#888;
		text-align:center;
		overflow:hidden;
	}
	.pullUp .pullUpIcon  {
		padding:10px 20px;
		background:url(/static/js/plugin/lazyload/pull-icon@2x.png) 0 0 no-repeat;
		-webkit-background-size:40px 80px; background-size:40px 80px;
		-webkit-transition-property:-webkit-transform;
		-webkit-transition-duration:250ms;	
	}
	.pullUp .pullUpIcon  {
		-webkit-transform:rotate(0deg) translateZ(0);
	}

	.pullUp.flip .pullUpIcon {
		-webkit-transform:rotate(0deg) translateZ(0);
	}

	.pullUp.loading .pullUpIcon {
		background-position:0 100%;
		-webkit-transform:rotate(0deg) translateZ(0);
		-webkit-transition-duration:0ms;

		-webkit-animation-name:loading;
		-webkit-animation-duration:2s;
		-webkit-animation-iteration-count:infinite;
		-webkit-animation-timing-function:linear;
	}

	@-webkit-keyframes loading {
		from { -webkit-transform:rotate(0deg) translateZ(0); }
		to { -webkit-transform:rotate(360deg) translateZ(0); }
	}
 </style>
<?php
ad($UsersID, 1, 1); //第一个数字参数代表广告位置：1顶部2底部；第二个数字参数代表广告位的编号，从后台查看
?>
<div id="shop_page_contents">
 <div id="cover_layer"></div>
 <link href='/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/page.css' rel='stylesheet' type='text/css' />
 <link href='/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/page_media.css' rel='stylesheet' type='text/css' />
 <link href='/static/js/plugin/flexslider/flexslider.css' rel='stylesheet' type='text/css' />
 <script type='text/javascript' src='/static/js/plugin/flexslider/flexslider.js'></script>
 <script type='text/javascript' src='/static/api/shop/js/index.js'></script>
 <script language="javascript">
  var shop_skin_data=<?php echo json_encode($json) ?>;
  $(document).ready(index_obj.index_init);
 </script>
 <div id="shop_skin_index">
   <div class="shop_skin_index_list banner" rel="edit-t01">
        <div class="img"></div>
    </div>
    <?php require_once('skin/index_info.php');?>
    <div class="clear"></div>
 </div>
 <div class="index_cate">
  <ul>
   <?php foreach($category_list as $kye=>$item):?>
   <li>
     <a href="<?=$shop_url.'category/'.$item['Category_ID'].'/'?>"><img data-url="<?=$item['Category_Img']?>" src="/static/js/plugin/lazyload/grey.gif"/> </a>
     <p><?=$item['Category_Name']?></p>           
   </li>
   <?php endforeach;?>
   <div class="clear"></div>
  </ul>
 </div>
 <?php require_once("skin/activelist.php"); ?>
 <div class="index_products">
  <h2>新品上市</h2>
  <div class="double_clear"></div>
  <ul id="more" style="overflow:hidden;"></ul>
  <div class="pullUp get_more" cate_id="" page="1"> <span class="pullUpIcon"></span><span class="pullUpLabel">点击加载更多...</span> </div>
 </div>
 <div class="index_products">
  <h2>热门推荐</h2>
  <div class="double_clear"></div>
  <ul id="more1" style="overflow:hidden;"></ul>
  <div class="pullUp get_more1" cate_id="1" page="1"> <span class="pullUpIcon"></span><span class="pullUpLabel">点击加载更多...</span> </div>
 </div>
</div>
<?php require_once('skin/distribute_footer.php'); ?>
<?php include("yh_index_ajax.php");?>
</body>
</html>
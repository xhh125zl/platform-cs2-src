<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/General_tree.php');


if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$base_url = base_url();
$UsersID = $_SESSION['Users_ID'];

$Kanjia_ID = $_GET['KanjiaID'];

//获取此砍价活动信息

$Activity = $DB->GetRs('kanjia','*',"where Users_ID='".$UsersID."'"." and Kanjia_ID='".$Kanjia_ID."'");
//获取此产品现价
$Product = $DB->GetRs('shop_products','*',"where Users_ID='".$UsersID."'"." and Products_ID='".$Activity['Product_ID']."'");

//获取分类列表
$DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ParentID=0 order by Category_Index asc");
$ParentCategory=array();

$i=1;
while($rsPCategory=$DB->fetch_assoc()){
  $ParentCategory[$i]=$rsPCategory;
  $i++;
}

$category_list = array(); 
foreach($ParentCategory as $key=>$value){
  $DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ParentID=".$value["Category_ID"]." order by Category_Index asc");
  if($DB->num_rows()>0){
    $category_list[$value["Category_ID"]]['name'] = $value["Category_Name"];
    while($rsCategory=$DB->fetch_assoc()){
       $category_list[$value["Category_ID"]]['children'][$rsCategory['Category_ID']] = $rsCategory['Category_Name'];
    }
   
  }else{
      $category_list[$value["Category_ID"]]['name'] = $value["Category_Name"];
	    $category_list[$value["Category_ID"]]['children'] = array();
  }
}

	
if($_POST)
{
	
	$Fromtime = strtotime($_POST['AccTime_S']);
	$Totime = strtotime($_POST['AccTime_E']);
	
	$Data=array(
		"Kanjia_Name"=>$_POST['Name'],
		"Users_ID"=>$UsersID,
		"Product_Name"=>$_POST['Products_Name'],
		"Product_ID"=>$_POST['Products_ID'],
		"Beginnum"=>$_POST['begin_num'],
		"Endnum"=>$_POST['end_num'],
		"Bottom_Price"=>$_POST['Bottom_Price'],
		"Fromtime"=>$Fromtime,
		"Totime"=>$Totime,
		"is_recommend"=>isset($_POST["is_recommend"])?$_POST["is_recommend"]:0,
		
	);
	
	$Flag=$DB->Set("kanjia",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Kanjia_ID=".$Kanjia_ID);
	if($Flag)
	{
		echo '<script language="javascript">alert("编辑成功");window.location="activity_list.php";</script>';
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
<title>微易宝</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script>
var base_url = "<?=$base_url?>";

</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/kanjia.js'></script>
    <div class="r_nav">
        <ul>
         <li ><a href="config.php">基本设置</a></li>
        <li class="cur" ><a href="activity_list.php">活动管理</a></li>
        <li ><a href="orders.php">订单管理</a></li>
        <li ><a href="commit.php">评论管理</a></li>
    	</ul>
    </div>
    
    <div id="products" class="r_con_wrap">
      <div class="control_btn">
    	<a href="activity_add.php" class="btn_green btn_w_120">新建活动</a>
      </div>
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form class="r_con_form" id="add_form" method="post" action="activity_edit.php?KanjiaID=<?=$Kanjia_ID?>">
        <div class="rows">
          <label>活动名称</label>
          <span class="input">
          <input type="text" name="Name" value="<?=$Activity['Kanjia_Name']?>" class="form_input" size="35" maxlength="100" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
       
        <div class="rows">
          <label>选择产品</label>
          <span class="input">
          <select id="Category" >
             <option value=''>--请选择--</option>
            <?php foreach($category_list as $key=>$item):?>
              <option value="<?=$key?>"><?=$item['name']?></option>
               <?php if(count($item['children'])>0):?>
              
                   <?php foreach($item['children'] as $cate_id=>$child):?>
                    <option value="<?php echo $cate_id;?>">&nbsp;&nbsp;&nbsp;&nbsp;<?=$child?></option>
                   <?php endforeach;?>
               <?php endif;?>
            <?php endforeach;?>
          </select>
          <input type="text"  id="keyword" placeholder="关键字" value="" class="form_input" size="35" maxlength="30" />
          <button type="button" id="search">搜索</button>
          
        
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>可选商品</label>
        
          <select size='10'  id="select_product" style="width:500px">
            
          </select>
        
 
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动商品</label>
          <span class="input">
          <input type="hidden" name="Products_ID" value="<?=$Activity['Product_ID']?>" id="Products_ID" />
          <input type="text" name="Products_Name" value="<?=$Activity['Product_Name']?>" id="Products_Name" class="form_input" size="35" maxlength="100" notnull />
          <input type="hidden" name="Products_Price" value="<?=$Product['Products_PriceX']?>"  id="Products_Price"/>
           <span id="Products_Price_Txt"><?=$Product['Products_PriceX']?></span>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        
         <div class="rows">
       	 <label>底价</label>
         <span class="input">
         	<input type="text" name="Bottom_Price" id="Bottom_Price" value="<?=$Activity['Bottom_Price']?>" size="10" class="form_input"  notnull />
         <font class="fc_red">*</font>
        </span>
         <div class="clear"></div>
       </div>
        
        
         <div class="rows">
          <label>随机砍价数区间</label>
          <span class="input">
        	<input type="text" name="begin_num" id="begin_num" value="<?=$Activity['Beginnum']?>" size="4" notnull />-
            <input type="text" name="end_num" id="end_num" value="<?=$Activity['Endnum']?>" size="4" notnull/>
			 <span>必须为正整数,且始价和终价不能相等</span>
          <font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
       </div>
       
       
        
        
       <div class="rows">
          <label>其他属性</label>
           <span class="input">
           推荐:
          <input type="checkbox" value="1" name="is_recommend"<?php echo empty($Activity["is_recommend"])?"":" checked" ?> />
          </span>
          <div class="clear"></div>
        </div>
        
       <div class="rows">
          <label>活动时间</label>
          
        <input type="text" name="AccTime_S" value="<?=date('Y-m-d H:i:s',$Activity['Fromtime'])?>" maxlength="20" notnull />-
        <input type="text" name="AccTime_E" value="<?=date('Y-m-d H:i:s',$Activity['Totime'])?>" maxlength="20" notnull/>
        
       
          <font class="fc_red">*</font>
          <div class="clear"></div>
       </div>
        
        
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>

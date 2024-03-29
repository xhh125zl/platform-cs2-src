<?php 
 require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
 require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$rsTypes =  $DB->get("shop_product_type","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Type_Index asc");
$shop_product_type_list = $DB->toArray($rsTypes);

$Attr_Option_List = array("唯一属性","单选属性","复选属性");
$Input_List = array("手工录入","从下面列表中选择(一行代表一个可选项)","多行文本框");

$base_url = base_url();

if($_POST)
{


	
	$Data = array(
	 	'Attr_Name'       => $_POST['Attr_Name'],
		'Users_ID' => $_SESSION['Users_ID'],
        'Type_ID'          => $_POST['Type_ID'],
        'Attr_Group'      => isset($_POST['Attr_Group'])?intval($_POST['Attr_Group']):'',
		'Sort_Order' => $_POST['Sort_Order'],
		'Attr_Input_Type' => $_POST['Attr_Input_Type'],
        'Attr_Values'     => isset($_POST['Attr_Values']) ? $_POST['Attr_Values'] : '',
        'Attr_Type'       => empty($_POST['Attr_Type']) ? '0' : intval($_POST['Attr_Type']),
   	
    );
	
	
	$Flag=$DB->Add("shop_attribute",$Data);
	if($Flag)
	{
		echo '<script language="javascript">alert("添加成功");window.location="shop_attr.php";</script>';
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
<title>好分销</title>
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
    <div class="r_nav">
      <ul>
        <li><a href="shop_attr.php">产品属性</a></li>
        <li class="cur"><a href="shop_attr_add.php">添加属性</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
<script language="javascript">
var base_url  =  '<?=$base_url?>';
$(document).ready(shop_obj.products_attr_add_init);

</script>
      <div class="attr">
        <div class="m_righter" style="margin-left:0px;">
          <form action="shop_attr_add.php" method="post" id="shop_attr_add_form">
            <h1>添加产品属性</h1>
            
            <div class="opt_item">
              <label>属性名称：</label>
              <span class="input">
              <input type="text" name="Attr_Name" value="" class="form_input" size="15" maxlength="30" notnull />
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            
            
            <div class="opt_item" id="product_type_html">
              <label>产品类型：</label>
              <span class="input">
              <select name="Type_ID" id="Type_ID" style="width:180px;" notnull>
               <option value="">全部类型</option>
               <?php foreach($shop_product_type_list as $key=>$product_type):?>
               	<option value="<?=$product_type['Type_ID']?>"><?=$product_type['Type_Name']?></option>
               <?php endforeach;?>
              </select>
              </span>
              <div class="clear"></div>
            </div>
            
           <div class="opt_item">
              <label>菜单排序：</label>
              <span class="input">
              <input type="text" name="Sort_Order" value="1" class="form_input" size="5" maxlength="30" notnull />
              <font class="fc_red">*</font>请输入数字</span>
              <div class="clear"></div>
            </div>
             
           <div class="opt_item">
              <label>类型：</label>
              <div class="input">
              	<?php foreach($Attr_Option_List as $key=>$item):?>
                	
					<?php if($key  == 0):?>
                    	<input type="radio" name="Attr_Type" value="<?=$key?>" checked />&nbsp;&nbsp;<span><?=$item?></span>&nbsp;&nbsp; 
				    <?php else: ?>
                    	<input type="radio" name="Attr_Type" value="<?=$key?>" />&nbsp;&nbsp;<span><?=$item?></span>&nbsp;&nbsp;
					<?php endif; ?>
					
				<?php endforeach; ?>
              	<p>选择"单选/复选属性"时，可以对商品该属性设置多个值，同时还能对不同属性值指定不同的价格加价，用户购买商品时需要选定具体的属性值。选择"唯一属性"时，商品的该属性值只能设置一个值，用户只能查看该值。</p>
              </div>
              <div class="clear"></div>
            </div>
            
            
           <div class="opt_item">
            	<label>录入方式:</label>
            	<div class="input">
                  		<?php foreach($Input_List as $key=>$item):?>
                	
					<?php if($key  == 0):?>
                    	<input type="radio" name="Attr_Input_Type" class="Attr_Input_Type" value="<?=$key?>" checked />&nbsp;&nbsp;<span><?=$item?></span>&nbsp;&nbsp; 
				    <?php else: ?>
                    	<input type="radio" name="Attr_Input_Type" class="Attr_Input_Type" value="<?=$key?>" />&nbsp;&nbsp;<span><?=$item?></span>&nbsp;&nbsp;
					<?php endif; ?>
					
				<?php endforeach; ?>
                </div>
            </div>
            
           <div class="opt_item">
          <label>可选值：</label>
          <span class="input">
          	<textarea name="Attr_Values" id="Attr_Values" disabled="true"></textarea>
          </span>
          <br/>
         
          <div class="clear"></div>
          每行一个属性值
        </div> 
       	        
            
            
     
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="添加属性" />
              <a href="javascript:void(0);" class="btn_gray" onClick="location.href='shop_attr.php'">返回</a></span>
              <div class="clear"></div>
            </div>
          </form>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
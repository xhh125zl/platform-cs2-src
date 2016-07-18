<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/biz/global.php');
 require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
 require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
 require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');


$rsTypes =  $DB->get("pintuan_cate","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Type_Index asc");
$shop_product_type_list = $DB->toArray($rsTypes);

$Attr_Option_List = array("唯一属性","单选属性","复选属性");
$Input_List = array("手工录入","从下面列表中选择(一行代表一个可选项)","多行文本框");

$base_url = base_url();

$AttrID = empty($_REQUEST['Attr_ID'])?0:$_REQUEST['Attr_ID'];
$rsAttr = $DB->GetRs("pintuan_attribute","*","where Users_ID='".$_SESSION["Users_ID"]."' and Attr_ID=".$AttrID);

$group_list = get_attr_groups($rsAttr['cate_id'],$_SESSION["Users_ID"]);

if($_POST)
{	
		$Type_ID_row = $DB->GetRs("pintuan_attribute","Attr_ID","where cate_id =".$_POST["cate_id"]);
		if($Type_ID_row && !empty($Type_ID_row)  && $_POST['typeid_re'] != $_POST['Type_ID']){
			echo '<script language="javascript">alert("该类型已经添加过关联属性,请先删除");window.location="javascript:history.back(-1)";</script>';
		exit;
		}
	$Data = array(
	 	'Attr_Name'       => $_POST['Attr_Name'],
		'Users_ID' => $_SESSION['Users_ID'],
        'cate_id'          => $_POST['cate_id'],
        'Attr_Group'      => isset($_POST['Attr_Group'])?intval($_POST['Attr_Group']):'',
		'Sort_Order' => $_POST['Sort_Order'],
		'Attr_Input_Type' => $_POST['Attr_Input_Type'],
        'Attr_Values'     => isset($_POST['Attr_Values']) ? $_POST['Attr_Values'] : '',
        'Attr_Type'       => empty($_POST['Attr_Type']) ? '0' : intval($_POST['Attr_Type']),
   	
    );
	
	$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Attr_ID=".$AttrID;
	$Flag=$DB->Set("pintuan_attribute",$Data,$condition);
	if($Flag)
	{
		echo '<script language="javascript">alert("更新成功");window.location="shop_attr.php";</script>';
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
<title>拼团</title>
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
        <li><a href="pintuan_attr.php">产品关联属性</a></li>
        <li class="cur"><a href="pintuan_attr_add.php">添加关联属性</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
<script language="javascript">
var base_url  =  '<?=$base_url?>';
$(document).ready(shop_obj.products_attr_edit_init);

</script>
      <div class="attr">
        <div class="m_righter" style="margin-left:0px;">
          <form action="shop_attr_edit.php" method="post" id="shop_attr_edit_form">
            <input type="hidden" name="Attr_ID" value="<?=$rsAttr['Attr_ID']?>"/>
            <h1>修改产品属性</h1>
            <div class="opt_item">
              <label>属性名称：</label>
              <span class="input">
              <input type="text" name="Attr_Name" value="<?=$rsAttr['Attr_Name']?>" class="form_input" size="15" maxlength="30" notnull />
              <font class="fc_red">*&nbsp;&nbsp;编辑格式:如果有多个属性的话，名称之间用'|'分割。如:颜色|尺码。</font></span>
              <div class="clear"></div>
            </div>
            
            
            <div class="opt_item" id="product_type_html">
              <label>产品类型：</label>
              <span class="input">
              <select name="Type_ID" id="Type_ID" style="width:180px;" notnull>
               <option value="">全部类型</option>
               <?php foreach($shop_product_type_list as $key=>$product_type):?>
               	
                	<option value="<?=$product_type['cate_id']?>" <?=($product_type['cate_id']  == $rsAttr['cate_id'])?'selected':''?>><?=$product_type['Type_Name']?></option>
               
			   <?php endforeach;?>
              </select>
              </span>
              <div class="clear"></div>
            </div>
            
            <?php if(!empty($rsAttr['Attr_Group'])):?>
            <div class="opt_item" id="attr_group_opt">
            	<label>属性组</label>
                <span class="input">
                <select name="Attr_Group">
                	<?php foreach($group_list as $key=>$item):?>
                   			<option value="<?=$key?>" <?=($key == $rsAttr['Attr_Group'])?'selected':''?>><?=$item?></option>
                	<?php endforeach;?>
                </select>
                </span>
                
                <div class="clear"></div>
            </div>
           <?php endif;?> 
           <div class="opt_item">
              <label>菜单排序：</label>
              <span class="input">
              <input type="text" name="Sort_Order" value="<?=$rsAttr['Sort_Order']?>" class="form_input" size="5" maxlength="30" notnull />
              <font class="fc_red">*</font>请输入数字</span>
              <div class="clear"></div>
            </div>
             
           <div class="opt_item">
              <label>类型：</label>
              <div class="input">
				<?php foreach($Attr_Option_List as $key=>$item):?>
                               <?php if($key  == 0):?> 
                               <?php elseif($key  == 1):?>    
                    					<input type="radio" name="Attr_Type" value="<?=$key?>" <?=($key == $rsAttr['Attr_Type'])?'checked':''?> />&nbsp;&nbsp;<span><?=$item?></span>&nbsp;&nbsp; 
							   <?php else: ?>
            
							   <?php endif; ?>
					
				<?php endforeach; ?>
              	<p>选择"单选属性"，可以对商品该属性设置多个值，同时还能对不同属性值指定不同的价格加价，用户购买商品时需要选定具体的属性值。</p>
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
					<?php endif; ?>
				<?php endforeach; ?>

                </div>
            </div>
            
            <div class="opt_item">
                  <br/>
            </div> 
       	    <input type="hidden" name="typeid_re" value="<?php echo $rsAttr['cate_id'];?>">    
            
            
     
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="修改属性" />
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
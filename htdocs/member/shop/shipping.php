<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');


$Pinyin = new Utf8pinyin();
$base_url = base_url();
$_SERVER['HTTP_REFERER'] =  $base_url.'member/shop/distribute_record.php';
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$action=empty($_REQUEST['action'])?'':$_REQUEST['action'];

$shopConfig = $DB->getRs('shop_config','*',"where Users_ID= '".$_SESSION['Users_ID']."'");

/*
 *$Product_Info 中包含件数，单个重量，购买单价
 *$Product_Info = array('Qty'=>$Qty,'Weight'=>$Weight,'Price'=>$Price);
 *
 */
 
$Product_Info = array('Qty'=>10,'Weight'=>'0.5','Price'=>100);


if(!empty($action))
{	
	if($action =='add_shipping'){
	
		$Shipping_Name = $_POST['Shipping_Name'];
		
		//如果已经存在这个快递公司，不可再添加
		$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Shipping_Name= '".$Shipping_Name."'";
		$shipping = $DB->getRs('shop_shipping_company','*',$condition);
		
		if($shipping){
			echo '<script language="javascript">alert("添加失败，提现方式不能重名");history.back();</script>';
			exit();
		}
		
		
		$data = array(
			  "Users_ID"=>$_SESSION["Users_ID"],
			  "Shipping_Name"=>$Shipping_Name,
			  "Shipping_Code"=>$Pinyin->str2py($Shipping_Name,TRUE,TRUE),
			  "Shipping_Business"=>'express',
			  "Shipping_Status"=>$_POST['Shipping_Status'],
			  "shipping_CreateTime"=>time(),
			  );
			  
		
		$DB->Add('shop_shipping_company',$data);	  
	}
	
	if($action == 'edit_shipping_company'){
		
		$Shipping_Name = $_POST['Shipping_Name'];
		
		$data = array(
			  "Users_ID"=>$_SESSION["Users_ID"],
			  "Shipping_Name"=>$Shipping_Name,
			  "Shipping_Code"=>$Pinyin->str2py($Shipping_Name,TRUE,TRUE),
			  "Shipping_Business"=>'express',
			  "Shipping_Status"=>$_POST['Shipping_Status']
			
			  );

		$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Shipping_ID=".$_POST["Shipping_ID"];				  
		$Flag = $DB->Set('shop_shipping_company',$data,$condition);
		//如果禁用此快递公司，则禁用其下属快递模板 
		$DB->Set('shop_shipping_template',array('Template_Status'=>$_POST['Shipping_Status']),$condition);
		
		if($Flag)
		{
			echo '<script language="javascript">alert("编辑成功");window.location="shipping.php";</script>';
		}else
		{
			echo '<script language="javascript">alert("编辑成功");history.back();</script>';
		}
		exit;	  
	}
	
	if($action=="del"){
		//查看所要删除的快递公司是否是系统配置的默认快递公司
	    $Config_Shipping_ID = !empty($shopConfig['Default_Shipping'])?$shopConfig['Default_Shipping']:0;
	
		//删除快递公司
		$Flag=$DB->Del("shop_shipping_company","Users_ID='".$_SESSION["Users_ID"]."' and Shipping_ID=".$_GET["Shipping_ID"]);

		$Shop_Config_Change = false;
        $shop_config_data = array();		
		if($Config_Shipping_ID == $_GET["Shipping_ID"]){
			$Shop_Config_Change = true;
			$default_company = Shipping_Company::where('Users_ID',$_SESSION["Users_ID"])->first();
			$default_shipping_id = !empty($default_company)?$default_company->Shipping_ID:0;
			$shop_config_data['Default_Shipping'] = $default_shipping_id;
		}
		
		//更新模板设置
	    $Shipping_List = json_decode($shopConfig['Shipping'],TRUE);
	    if(!empty($Shipping_List)&&array_key_exists($_GET["Shipping_ID"],$Shipping_List)){
			$Shop_Config_Change = true;
			unset( $Shipping_List[$_GET["Shipping_ID"]]);
			$shop_config_data['Shipping'] = json_encode($Shipping_List,JSON_UNESCAPED_UNICODE );
		}
		
		if($Shop_Config_Change){
			Shop_Config::find($_SESSION["Users_ID"])->update($shop_config_data);
		}
	    
		//删除此快递公司下属模板
		$DB->Del('shop_shipping_template',"Users_ID='".$_SESSION["Users_ID"]."' and Shipping_ID=".$_GET["Shipping_ID"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="shipping.php";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}


$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
$condition .= " order by Shipping_CreateTime";
$rsShippings = $DB->getPage("shop_shipping_company","*",$condition,$pageSize=10);
$shipping_list = $DB->toArray($rsShippings);;

$Business_List = array('express'=>'快递','common'=>'平邮');

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.metadata.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.zh_cn.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type="text/javascript">
	var base_url = '<?=$base_url?>';	
</script>

</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shipping.js'></script>
    <div class="r_nav">
      <ul>

        <li class="cur" > <a href="shipping.php">快递公司管理</a> </li>
        <li ><a href="shipping_template.php">快递模板管理</a></li>
       
      </ul>
    </div>
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script> 
     <script type='text/javascript' src='/static/js/inputFormat.js'></script>
    <script language="javascript">
	$(document).ready(function(){shipping_obj.shop_shiping_init()});
	
</script>
    <div id="update_post_tips"></div>
    <div id="user" class="r_con_wrap">
      <div class="control_btn">
      <a href="javascript:void(0)" id="create_shipping_btn" class="btn_green btn_w_120">添加</a> 
    
      </div>
      
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">序号</td>
            <td width="8%" nowrap="nowrap">快递公司</td>

    
            <td width="5%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap">添加时间</td>
            <td width="8%" nowrap="nowrap" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
      
		  
	<?php foreach($shipping_list as $key=>$shipping):?>
           <tr ShippingID="<?php echo $shipping['Shipping_ID'] ?>">
            <td><?=$shipping['Shipping_ID']?></td>
            <td>
            	<?=$shipping['Shipping_Name']?>
            </td>
            
           
            <td><?php if($shipping['Shipping_Status'] ==1 ){ echo '<img src="/static/member/images/ico/yes.gif"/>';}else{echo '<img src="/static/member/images/ico/no.gif"/>';}?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$shipping['Shipping_CreateTime']) ?></td>
            
            <td nowrap="nowrap" class="last">
            <a href="javascript:void(0)" shipping-id="<?=$shipping['Shipping_ID']?>" class="shipping_edit_btn"><img src="/static/member/images/ico/mod.gif" alt="修改" align="absmiddle"></a>
            <a href="shipping.php?action=del&Shipping_ID=<?php echo $shipping['Shipping_ID'] ?>" onClick="if(!confirm('删除后不可恢复，且其下属分销模板会被一并删除,继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
      <?php endforeach; ?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
  
  <div id="mod_create_shipping" class="lean-modal lean-modal-form">
  		 <div class="h">添加快递公司<span></span><a class="modal_close" href="#"></a></div>
         
         <form class="form" action="shipping.php" method="post" id="create_shipping_form" name="mod_create_shipping">
            <p class="rows">
            <label for="Shipping_Name">名称</label>
            <input type="text" value=""  name="Shipping_Name"  required="true" />
            </p> 
           
            <p class="rows">
            	<label>状态</label>
                <input name="Shipping_Status" value="1"  type="radio" checked>&nbsp;&nbsp;可用
               	<input name="Shipping_Status" value="0"  type="radio">&nbsp;&nbsp; 不可用
            </p>   
            <p class="rows">
            	<label></label>
        		<input type="submit" value="确定提交" name="submit_btn">
      		</p>
      <input type="hidden" name="action" value="add_shipping">  
         </form>
  </div>
  
  <div id="mod_edit_shipping" class="lean-modal lean-modal-form">
    <div class="h">修改快递公司信息<span></span><a class="modal_close" href="#"></a></div>
    
    <div id="shipping_company_edit_content" style="min-height:200px;">
    	 <p style="margin-left:20px;">正在获取信息...<p>
    </div>
    
  </div>
  
  

</div>
</body>
</html>
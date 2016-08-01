<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if($_POST){ 
  if($_POST["Isdraw"]==0) {
          if($_POST['T_count']==0) 
          {
            echo '<script language="javascript">alert("允许中奖团数  不能为0!");history.back();</script>'; exit();
          }
  }else{
      $_POST['T_count']=0;
      $_POST['ratio'] =0;
  }
  
  if(!is_numeric($_POST["PriceD"])){
      echo '<script language="javascript">alert("单购价请填写数字");history.back();</script>';
      exit;
  }
  
  if(!is_numeric($_POST["PriceT"])){
      echo '<script language="javascript">alert("团购价请填写数字");history.back();</script>';
      exit;
  }
  
  if(!isset($_POST["JSON"])){
    echo '<script language="javascript">alert("请上传商品图片");history.back();</script>';
    exit;
  }
  
  if(!empty($_POST['attr_count_list'])){
            $attrCount = array_sum($_POST['attr_count_list']);
            $CountTotal = $_POST['Count'];
            if($attrCount != $CountTotal){
                echo '<script language="javascript">alert("总库存应该等于属性库存之和！");history.back();</script>';
                exit;
            }
        }

    $str=array();
    $str[0]=$_POST['TypeID'];
    $Category_IDs=$DB->get('pintuan_category','cate_id',"  where 
    cate_id=(SELECT parent_id FROM pintuan_category WHERE cate_id='".$_POST['TypeID']."' ) and Users_ID='{$UsersID}'");
    
   while ( $res=$DB->fetch_assoc()) {
      $str[]=$res['cate_id'];
   }
   
    $pintuanid=$DB->query('SELECT IFNULL(MAX(Products_ID),"0") from pintuan_products');
    $REG=$DB->fetch_assoc($pintuanid);
    $StartTime=$_POST['starttime'];
    $EndTime=$_POST['stoptime'];
    $image= json_encode($_POST['JSON']);
    $t_count=isset($_POST['T_count']) && !empty($_POST['T_count'])?$_POST['T_count']:0;
  $Data=array(
    "Products_Index"=>empty($_POST["Index"]) ? 9999 : intval($_POST["Index"]),
    "Products_Name"=>addslashes($_POST['Name']),
    "Products_Category"=>','.implode(",",$str).',',
    "Products_Count"=>$_POST["Count"]?$_POST["Count"]:10,
    "Products_PriceT"=>empty($_POST['PriceT'])?"0":$_POST['PriceT'],
    "Products_PriceD"=>empty($_POST['PriceD'])?"0":$_POST['PriceD'],
    "Products_Profit"=>empty($_POST['Products_Profit'])?"0":$_POST['Products_Profit'],
    "commission_ratio"=>isset($_POST["commission_ratio"])?intval($_POST["commission_ratio"]):0,
    "nobi_ratio"=>isset($_POST["nobi_ratio"])?intval($_POST["nobi_ratio"]):0,
    "platForm_Income_Reward" => isset($_POST['platForm_Income_Reward'])?intval($_POST['platForm_Income_Reward']) : 0,
    "area_Proxy_Reward" => isset($_POST['area_Proxy_Reward']) ? intval($_POST['area_Proxy_Reward']) : 0,
    "sha_Reward" => isset($_POST['sha_Reward']) ? intval($_POST['sha_Reward']) : 0,
    "Products_Distributes"=>empty($_POST['Distribute'])?" ":json_encode($_POST['Distribute'],JSON_UNESCAPED_UNICODE),
    "Products_JSON"=> $image,
    "Products_BriefDescription"=>htmlspecialchars($_POST['BriefDescription'], ENT_QUOTES),
    "Shipping_Free_Company"=>isset($_POST["Shipping_Free_Company"]) ? intval($_POST["Shipping_Free_Company"]) : 0,
    "Products_IsNew"=>(($_POST["Radio"])==0)?(($_POST["Radio"])==0):0,
    "Products_IsHot"=>(($_POST["Radio"])==1)?(($_POST["Radio"])==1):0,
    "Products_IsRecommend"=>(($_POST["Radio"])==2)?(($_POST["Radio"])==2):0,
    "Products_Description"=>htmlspecialchars($_POST['Description'], ENT_QUOTES),
    "Products_Status"=>0,   
    "Products_CreateTime"=>time(),    
    // "Products_Parameter"=>json_encode(empty($_POST["Products_Parameter"]) ? 0: $_POST["Products_Parameter"],JSON_UNESCAPED_UNICODE),
    "Products_Parameter"=>'',
    "Products_Weight" => $_POST['Weight']?$_POST['Weight']:0,
    "order_process"=>$_POST["ordertype"],
    "is_buy"=>$_POST["Isbuy"],
    "Is_Draw"=>$_POST["Isdraw"],
    "Award_rule"=>$_POST["Awardrule"],
    "Ratio"=>!isset($_POST["ratio"])?0:$_POST["ratio"],
    "people_num"=>$_POST["Peoplenum"],
    "Products_Sales"=>$_POST["Psales"], 
    "starttime"=>strtotime($StartTime),
    "stoptime"=>strtotime($EndTime) + 86398,
    "people_once"=>isset($_POST["people_once"])?$_POST["people_once"]:1,
    "Products_pinkage"=>!isset($_POST["pinkage"])?0:$_POST["pinkage"],
    "Products_refund"=>!isset($_POST["refund"])?0:$_POST["refund"],
    "Products_compensation"=>!isset($_POST["compensation"])?0:$_POST["compensation"],
    "Team_Count"=>$t_count,
    /*edit in 20160318*/
  );
  $Data["Users_ID"] = $UsersID;
  $Data["Biz_ID"] = $BizID;
  //产品结算形式

  if($rsBiz["Finance_Type"]==0){//商品按交易额比例
      $Data["Products_FinanceType"] = 0;
      $Data["Products_FinanceRate"] = $rsBiz["Finance_Rate"];
      $Data["Products_PriceSd"] = number_format($_POST['PriceD'] * (1-$rsBiz["Finance_Rate"]/100),2,'.','');
      $Data["Products_PriceSt"] = number_format($_POST['PriceT'] * (1-$rsBiz["Finance_Rate"]/100),2,'.','');
  }else{//商品按供货价
      if($_POST["FinanceType"]==0){//商品按交易额比例
          if(!is_numeric($_POST["FinanceRate"]) || $_POST["FinanceRate"]<=0){
              echo '<script language="javascript">alert("网站提成比例必须大于零！");history.back();</script>';
              exit();
          }
          $Data["Products_FinanceType"] = 0;
          $Data["Products_FinanceRate"] = $_POST["FinanceRate"];
          $Data["Products_PriceSd"] = number_format($_POST['PriceD'] * (1-$_POST["FinanceRate"]/100),2,'.','');
          $Data["Products_PriceSt"] = number_format($_POST['PriceT'] * (1-$_POST["FinanceRate"]/100),2,'.','');
      }else{//商品按供货价
          if(!is_numeric($_POST["PriceS"]) || $_POST["PriceS"]<=0 || $_POST["PriceS"]>$_POST['PriceT']){
              echo '<script language="javascript">alert("供货价格必须大于零，且小于团购价格！");history.back();</script>';
              exit();
          }
          if(!is_numeric($_POST["PriceS"]) || $_POST["PriceS"]<=0 || $_POST["PriceS"]>$_POST['PriceD']){
              echo '<script language="javascript">alert("供货价格必须大于零，且小于单购购价格！");history.back();</script>';
              exit();
          }
          $Data["Products_FinanceType"] = 1;
          $Data["Products_PriceSd"] = $_POST['PriceS'];
          $Data["Products_PriceSt"] = $_POST['PriceS'];
      }
  }
  $Flag=$DB->Add("pintuan_products",$Data);
  $product_id = mysql_insert_id();
  if($Flag){
      if(isset($_POST["cardids"]) && $_POST["cardids"]){
            $idcards = $_POST["cardids"];
            $idcards = trim($idcards,",");
            $DB->Set("pintuan_virtual_card", [ 'Products_Relation_ID' => $product_id ],"WHERE Users_ID='{$UsersID}' AND Card_ID IN({$idcards})");
      }
     echo '<script language="javascript">alert("添加成功");window.location="products.php";</script>';
  }else{
    echo '<script language="javascript">alert("添加失败");history.back();</script>';
  }
  exit;
}else{
  $shop_config = shop_config($UsersID);  
  $dis_config = dis_config($UsersID);

  $Shop_Commision_Reward_Arr = array();
  if (!is_null($shop_config['Shop_Commision_Reward_Json'])) 
  {
    $Shop_Commision_Reward_Arr = json_decode($shop_config['Shop_Commision_Reward_Json'], true);
  }
}
$starttime = strtotime(date("Y-m-d")." 00:00:00");
$endtime = time();
$day = date("d");
$monthStarttime = strtotime(date("Y-m-d")." 00:00:00")-($day-1)*86400;
$monthEndtime = strtotime(date("Y-m-d")." 23:59:59");
if(isset($_GET["search"]) && $_GET["search"]==1){
    $SearchType = $_GET['SearchType'];
    $searchStarttime = strtotime($_GET['starttime']."00:00:00");
    $searchStoptime = strtotime($_GET['stoptime']."23:59:59")+1;
    $days = ($searchStoptime-$searchStarttime)/86400;
    $searchEnddate = $_GET['stoptime'];
    
}else{
    $day = date("d");
    $searchStarttime = strtotime(date("Y-m-d")." 00:00:00")-($day-1)*86400;
    $searchStoptime = strtotime(date("Y-m-d")." 23:59:59");
    $searchStartdate = date('Y-m-d',$searchStarttime);
    $searchEnddate = date('Y-m-d',$searchStoptime);
    $days = ($searchStoptime+1-$searchStarttime)/86400;
    $SearchType = 'reg';
}
if($searchStoptime<=$searchStarttime){
    echo '<script language="javascript">alert("截止时间不能小于开始时间");history.back();</script>';
    exit;
}
for($i=$days;$i>0;$i--){ 
    $fromtime = strtotime($searchEnddate."00:00:00")-($i-1)*86400;
    $dates[] = date("m-d", $fromtime);
    $fromtimes[] =  $fromtime;
}

function treelist($arr,$html='--',$level=0){
    static $i = 0;
    if($arr){
        foreach($arr as $k=>$v){
            
            if($v['parent_id']==0){
                echo '<option value="'.$v["cate_id"].'"'.'>'.$v["cate_name"].'</option>';
            }else{
                foreach ($arr as $kk=>$vv){
                    if($v['parent_id']==$vv['cate_id']){
                        echo '<option value="'.$v["cate_id"].'"'.'>'.str_repeat("——",1).$v["cate_name"].'</option>';
                    }
                }
            }
        }
        
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
<script type='text/javascript' src='/static/member/js/products_attr_helper.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script type='text/javascript' src='/static/member/js/shop.js'></script>
<script type='text/javascript' src="/static/js/plugin/laydate/laydate.js"></script>
<script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
<script>
var Browser = new Object(); 
KindEditor.ready(function(K) {
  K.create('textarea[name="Description"]', {
    themeType : 'simple',
    filterMode : false,
    uploadJson : '/member/upload_json.php?TableField=web_column&Users_ID=<?php echo $UsersID;?>',
    fileManagerJson : '/member/file_manager_json.php',
    allowFileManager : true,
    
  });
  var editor = K.editor({
    uploadJson : '/member/upload_json.php?TableField=web_article&Users_ID=<?php echo $UsersID;?>',
    fileManagerJson : '/member/file_manager_json.php',
    showRemote : true,
    allowFileManager : true,
  });
  K('#ImgUpload').click(function(){
    if(K('#PicDetail').children().length>=5){
      alert('您上传的图片数量已经超过5张，不能再上传！');
      return;
    }
    editor.loadPlugin('image', function() {
      editor.plugin.imageDialog({
        clickFn : function(url, title, width, height, border, align) {
          K('#PicDetail').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" /></a><a onclick="return imagedel(this);"><span>删除</span></a><input type="hidden" name="JSON[ImgPath][]" value="'+url+'" /></div>');
          editor.hideDialog();
        }
      });
    });
  });
  K('#PicDetail div span').click(function(){
    K(this).parent().remove();
  });
});

$(document).ready(shop_obj.products_add_init);
$(document).ready(function(){
    $('.rows input[name=ordertype]').click(function(){
      var sVal = $('.rows input[name=ordertype]:checked').val();
      if (sVal == 2) { 
          layer.open({
              type: 2,
              area: ['800px', '500px'],
              fix: false,
              maxmin: true,
              content: '/member/pintuan/virtual_card_select.php'
          });

          $('input[name=Count]').val('0').attr('readonly', 'readonly');
      };
    });
});
</script>
<style type="text/css">
.dislevelcss{float:left;margin:5px 0px 0px 8px;text-align:center;border:solid 1px #858585;padding:5px;}
.dislevelcss th{border-bottom:dashed 1px #858585;font-size:16px;}
</style>
</head>
<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js'></script>
    <div class="r_nav">
       <ul>
        <li class="cur"><a href="./products.php">产品管理</a></li>
        <li><a href="./orders.php">订单管理</a></li>
        <li><a href="./comment.php">评论管理</a></li>
        <li><a href="./virtual_card.php">虚拟卡密管理</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
      <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
      <script src="/static/js/plugin/laydate/laydate.js"></script>

      <form id="product_add_form" class="r_con_form skipForm" method="post" action="product_add.php">
        
        <div class="rows">
          <label>产品名称</label>
          <span class="input">
          <input type="text" name="Name" value="" class="form_input" size="35" maxlength="100" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>

    <div class="rows" id="type_html">
   <label>产品类型：</label>
   <span class="input">
   <select name="TypeID" style="width:180px;" id="Type_ID" notnull>
    <option value="">请选择类型</option>
      <?php   
        $result = $DB->get("pintuan_category","*","where Users_ID='{$UsersID}' order by  sort asc");
        $catelist = array();
        while($rsType= $DB->fetch_assoc($result)){
           $catelist[]=$rsType;
        }
        treelist($catelist);
        ?>
     
   </select>
   <font class="fc_red">*</font></span>
   <div class="clear"></div>
</div>
        <div class="rows">
          <label>产品价格</label>
          <span class="input price"> 单购价格:￥
          <input type="text" name="PriceD" value="" class="form_input" size="5" maxlength="10" notnull />
                 团购价格:￥
          <input type="text" name="PriceT" value="" class="form_input" size="5" maxlength="10" notnull />
          </span>
          <div class="clear"></div>
        </div>
        <?php if($rsBiz["Finance_Type"]==1){?>
        <div class="rows">
          <label>财务结算类型</label>
          <span class="input">
              <input type="radio" name="FinanceType" value="0" id="FinanceType_0" onClick="$('#PriceS').hide();$('#FinanceRate').show();" checked /><label for="FinanceType_0"> 按交易额比例</label>&nbsp;&nbsp;<input type="radio" name="FinanceType" value="1" id="FinanceType_1" onClick="$('#FinanceRate').hide();$('#PriceS').show();" /><label for="FinanceType_1"> 按供货价</label><br />
          <span class="tips">注：若按交易额比例，则网站提成为：产品售价*比例%</span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="FinanceRate">
          <label>网站提成</label>
          <span class="input">
          <input type="text" name="FinanceRate" value="" class="form_input" size="10" /> %
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="PriceS" style="display:none">
          <label>供货价</label>
          <span class="input">
          <input type="text" name="PriceS" value="" class="form_input" size="10" /> 元
          </span>
          <div class="clear"></div>
        </div>
		<?php }?>
        <div class="rows">
          <label>产品重量</label>
          <span class="input">
         <input type="text" name="Weight" value="" notnull class="form_input" size="5" />&nbsp;&nbsp;千克 <font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
  
        <div class="rows">
          <label>产品图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="ImgUpload" value="添加图片" style="width:80px;" notnull />
            </div>
            <div class="tips">共可上传<span id="pic_count">5</span>张图片，图片大小建议：640*640像素</div>
            <div class="clear"></div>
          </div>
          </span>
          <div class="img" id="PicDetail"></div>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea name="BriefDescription" class="briefdesc" notnull ></textarea>
          </span>
          <div class="clear"></div>
        </div>
       
        <div id="propertys" display="none"></div> 
        <div class="rows">
          <div class="rows">
          <label>商家信誉</label>
          <span class="input">
          <label>包邮&nbsp;&nbsp;<input name="pinkage" type="checkbox"  value="1" />&nbsp;&nbsp;</label>
          <label>七天退款&nbsp;&nbsp;<input name="refund" type="checkbox"  value="1" />&nbsp;&nbsp;</label>
          <label>假一赔十&nbsp;&nbsp;<input name="compensation" type="checkbox"  value="1" />&nbsp;&nbsp;</label>
        </div>
         <div class="rows">
          <label>商品属性</label>
          <span class="input">
          <label>新品&nbsp;<input type="radio" checked  value="0" name="Radio"></label>&nbsp;&nbsp;
          <label>热销&nbsp;<input type="radio" value="1" name="Radio"></label>&nbsp;&nbsp;
          <label>促销&nbsp;<input type="radio" value="2" name="Radio"></label>
          <div class="clear"></div>
        </div>
        <div class="rows">
        <div class="rows">
          <label>订单流程</label>
          <span class="input" style="font-size:12px; line-height:22px;">
              <input type="radio" id="order_0" value="0" name="ordertype" checked /><label for="order_0"> 实物订单&nbsp;&nbsp;( 买家下单 -> 买家付款 -> 商家发货 -> 买家收货 -> 订单完成 ) </label><br />
              <!-- 
              <input type="radio" id="order_1" value="1" name="ordertype" /><label for="order_1"> 虚拟订单&nbsp;&nbsp;( 买家下单 -> 买家付款 -> 系统发送消费券码到买家手机 -> 商家认证消费 -> 订单完成 ) </label><br /> 
              <input type="radio" id="order_2" value="2" name="ordertype" /><label for="order_2"> 其他&nbsp;&nbsp;( 买家下单 -> 买家付款 -> 订单完成 ) </label>
               -->
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>是否支持单购</label>
          <span class="input">
          <label>既支持单购又支持团购&nbsp;<input type="radio"  value="1" name="Isbuy"></label>&nbsp;&nbsp;
          <label>仅支持团购&nbsp;<input type="radio" checked  value="0" name="Isbuy"></label>&nbsp;&nbsp;&nbsp;&nbsp;
          <span class="tips" >注:抽奖商品不支持单独购买</span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>拼团人数</label>
          <span class="input">
          <input type="text" name="Peoplenum" value="10" class="form_input" size="5" maxlength="10" /> <span class="tips" />&nbsp;注:若不限则填写0.</span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>累计销量</label>
          <span class="input">
          <input type="text" name="Psales" value="0" class="form_input" size="5" maxlength="100" /> <span class="tips" />&nbsp;</span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>是否支持抽奖</label>
        <table cellspacing="1" cellpadding="6" class="tb">  
            <tr>  
                <td class="tr">
                <input type="radio" name="Isdraw" id="#draw" value="1" checked/>不支持抽奖    
                <input type="radio" name="Isdraw" value="0"/> 支持抽奖</td>
            </tr>  
            <tr id="333">  
                <td class="tl"><span color="f_red">抽奖规则</span></td>  
                <td class="tr">
         <span> <textarea name="Awardrule" id="Awardrule" value="" class="briefdesc" ></textarea>
          </span>
            </tr>  
            <tr id="444">  
                <td class="tl"><span color="f_red">允许中奖团数</span></td>  
                <td class="tr">
                <input type="text" size="8" name="T_count" value=""/>
                <input type="hidden" size="6" name="ratio" id="ratio" value="0" /></td>
                <span>
                <td></td></span>
            </tr>  
        </table>  
          <div class="clear"></div>
        </div>
        <div id="store_part" style="display:none">
          <div class="rows">
                <div class="clear"></div>
            </div>
            <div class="rows">
                <div class="clear"></div>
            </div>
        </div>
        <div class="rows">
          <div class="clear"></div>
        </div>
        <div class="rows">
          <input type="hidden" name="people_once" value="1"  /> 
        </div>
          <div class="rows">
          <label>商品库存</label>
          <span class="input">
          <input type="text" name="Count" value="100" class="form_input" size="5" maxlength="10" notnull /> <span class="tips"></span>
          </span>
          <div class="clear"></div>
        <div class="rows">
          <label>拼团有效时间</label>
           <span class="input time">
          <div class="l">
              <div class="form-group">
                  <div class="input-group" id="reportrange" style="width:auto">
                  <input placeholder="开始时间" class="laydate-icon" name="starttime" value="<?php echo ($_GET)?$_GET['starttime']:"$searchStartdate"?>" onclick="laydate()">-
                  <input placeholder="截止时间" class="laydate-icon" name="stoptime" value="<?php echo ($_GET)?$_GET['stoptime']:"$searchEnddate"?>" onclick="laydate()">
                  </div>
              </div>
          </div>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>详细介绍</label>
          <span class="input">
          <textarea class="ckeditor" name="Description" style="width:700px; height:300px;" notnull/></textarea>
          </span>
          <div class="clear"></div>
        </div> 
             
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="goods.php" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
        <input type='hidden' value='' id='cardids' name='cardids' />
        <input type="hidden" id="UsersID" value="<?=$UsersID ?>" />
        <input type="hidden" id="ProductsID" value="0">        
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">  
        $(document).ready(function(){
           		if($("#draw").has('checked')){  
                    $("#333").hide();  
                    $("#444").hide();  
                }else{   
                    $("#333").show();  
                    $("#444").show();  
                }
           		
        })     
        $("input[name=Isdraw]").click(function(){  
              var type=$(this).val();  
                if(type=='1'){  
                    $("#333").hide();  
                    $("#444").hide();  
                }else{   
                    $("#333").show();  
                    $("#444").show();
                }     
        }); 
        
        function imagedel(o) {
    $(o).parent().remove();
    return false;
  }

function imagedel1(i) {
    $('.imagedel' + i).remove();
    return false;
  }
</script>  
</body>
</html>
                  
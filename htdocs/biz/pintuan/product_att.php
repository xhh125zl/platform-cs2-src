<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/biz/global.php');


if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("pintuan_attribute","Users_ID='".$_SESSION["Users_ID"]."' and Attr_ID=".$_GET["Attr_ID"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="shop_attr.php";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}
$Attr_Option_List = array("唯一属性","单选属性","复选属性"); 
$Input_List = array("手工录入","从下面列表中选择(一行代表一个可选项)","多行文本框");

function get_type($typeid){
	global $DB;
	$r = $DB->GetRs("pintuan_cate","*","where cate_id='".$typeid."'");
	return $r ? $r['Type_Name'] : "全部";
}

//获取分类列表
$lists = array();
$rsTypes = $DB->get("pintuan_cate","cate_id,cate_name","where Users_ID='".$_SESSION["Users_ID"]."' order by Type_Index asc");
$lists = $DB->toArray($rsTypes);


?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href="/static/style.css" rel="stylesheet" type="text/css" />
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
    <link href='/static/member/css/weicbd.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="pintuan_attr.php">产品关联属性</a></li>
        <li class=""><a href="pintuan_attr_add.php">添加关联属性</a></li>
      </ul>
    </div>
    
    
    <div id="attr" class="r_con_wrap"> 
     
     <form class="search" id="search_form" method="get" action="?">
     
        按商品类型显示：
        <select name="Type_ID">
          <option value="0">--请选择--</option>
          <?php foreach($lists as $key=>$item):?>
          <option value="<?=$item['cate_id']?>"><?=$item['Type_Name']?></option>
		  <?php endforeach;?>
        </select>
        
        <input class="search_btn" value="显示" type="submit">
    
      </form>
      
      <div class="property">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mytable">
          <tr bgcolor="#f5f5f5">
            <td width="50">序号</td>
            <td width="50" align="center"><strong>排序</strong></td>
            <td width="100" align="center"><strong>属性名称</strong></td>
            <td align="center" width="80"><strong>所属类型</strong></td>
            <td width="100" align="center"><strong>类型</strong></td>
            <td align="center"><strong>属性值</strong></td>
            <td width="100" align="center"><strong>操作</strong></td>
          </tr>
          <?php
		    $lists = array();
			
			$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
			
			if(!empty($_GET['cate_id'])){
				$Type_ID = $_GET['cate_id'];	
				$condition .= " And cate_id =".$Type_ID;
			}
			
			$condition .= " order by Attr_ID desc";
		     
			$rsList = $DB->getPage("pintuan_attribute","*",$condition,10);
			
			$lists = $DB->toArray($rsList);			
			foreach($lists as $k=>$rsAttr){
			?>
          <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';">
            <td align="center"><?=$rsAttr["Attr_ID"];?></td>
            <td align="center"><?php echo $rsAttr["Sort_Order"]; ?></td>
             <td align="center"><?php echo $rsAttr["Attr_Name"]; ?></td>
            <td align="center"><?php echo get_type($rsAttr["cate_id"]); ?></td>
            
            <td align="center"><?php echo $Attr_Option_List[$rsAttr["Attr_Type"]];?></td>
            <td><?php 
				
				if(!empty($rsAttr["Attr_Values"])){
					$values = str_replace("\r", ' ', $rsAttr['Attr_Values']);
					echo $values;
				}
			?></td>
            <td align="center"><a href="pintuan_attr_edit.php?Attr_ID=<?php echo $rsAttr["Attr_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="pintuan_attr.php?action=del&Attr_ID=<?php echo $rsAttr["Attr_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
          <?php
}?>
        </table>
        <div class="blank20"></div>
      	<?php $DB->showPage(); ?>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if(IS_GET && isset($_GET["action"]) && $_GET["action"]=="del"){
    $Active_ID = intval($_GET["Active_ID"]);
    $Flag=$DB->Del("active","Users_ID='{$UsersID}' AND Active_ID='{$Active_ID}'");
    if($Flag){
        sendAlert("删除成功!",$_SERVER['HTTP_REFERER'],3);
	}else{
	    sendAlert("删除失败!",$_SERVER['HTTP_REFERER'],3);
	}
}
if(IS_AJAX && isset($_POST['action']) && $_POST['action']=='adult')
{
    $id = $_POST['id'];
    $rsBizActive = $DB->GetRs("biz_active","*","WHERE ID={$id}");
    $status = $rsBizActive['Status']==1?2:($rsBizActive['Status']==2?3:2);
    $flag = $DB->Set("biz_active", ['Status' => $status], "WHERE ID='{$id}'");
    if($flag){
        $rsBizActive = $DB->GetRs("biz_active","*","WHERE ID={$id}");
        die(json_encode([ 'code'=>1,'data'=>['status'=>$rsBizActive['Status']]]));
    }else{
        die(json_encode([ 'code'=>0,'data'=>['status'=>$rsBizActive['Status']]]));
    }
}
$activeid = isset($_GET['activeid'])?$_GET['activeid']:0;
$lists = array();
$condition = " LEFT JOIN active AS a ON b.Active_ID=a.Active_ID  LEFT JOIN biz AS bz ON b.Biz_ID=bz.Biz_ID WHERE b.Users_ID='{$UsersID}' AND b.Active_ID={$activeid}  ORDER BY b.ID DESC";
$result = $DB->getPages("biz_active as b","a.Type_ID,a.Active_Name,a.stoptime,a.starttime,b.*,bz.Biz_Account,bz.Biz_Name",$condition,10);
$lists = $DB->toArray($result);
$Status = ['未开始','申请中','已同意','已拒绝','已结束'];
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
<script>
function view(id)
{
	layer.open({
        type: 2,
        area: ['600px', '500px'],
        fix: false,
        maxmin: true,
        content: '/member/active/active_view.php?id='+id
    });
}

function adult(id)
{
	$.post("/member/active/biz_active.php",{id:id,action:'adult'},function(data){
		if(data.code==1){
			var html = "";
			location.reload();
			
		}else{
			alert("修改失败");
		}
	},'json');
}
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div id="products" class="r_con_wrap">
      
      <table align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="48px" align="center"><strong>ID</strong></td>
            <td width="48px" align="center"><strong>活动名称</strong></td>
            <td align="center" width="66px"><strong>活动类型</strong></td>
            <td align="center" width="66px"><strong>商家</strong></td>
            <td align="center" width="66px"><strong>活动参与者列表</strong></td>
            <td align="center" width="66px"><strong>申请时间</strong></td>
            <td width="50px" align="center"><strong>活动状态</strong></td>
            <td width="70px" align="center"><strong>操作</strong></td>
          </tr>
          </tr>
        </thead>
        <tbody>
    	<?php 
    	  $time = time();
		  foreach($lists as $k => $v){
		      if(strtotime(date('Y-m-d 23:59:59',$v['stoptime']))<$time)
		      {
		          $v['Status'] = 4;
		      }
	    ?>      
          <tr>
            <td nowrap="nowrap" class="id"><?=$v["ID"] ?></td>
            <td nowrap="nowrap" class="id"><?=$v["Active_Name"] ?></td>
            <td nowrap="nowrap"><?=$ActiveType[$v["Type_ID"]] ?></td>
            <td nowrap="nowrap"><?=$v["Biz_Name"] ?></td>
            <td><a href="#" onclick="view(<?=$v["ID"]?>)">查看</a></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d",$v["starttime"]); ?></td>
            <td nowrap="nowrap"><?=$Status[$v["Status"]]; ?></td>
            <td class="last" nowrap="nowrap" id="adult">
            	<a href="#" onclick="adult(<?=$v["ID"] ?>)"><?=$v['Status']==2?'拒绝审核':'同意审核' ?></a>
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
</body>
</html>
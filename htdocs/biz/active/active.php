<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if(IS_GET && isset($_GET['action']) && $_GET['action']=='del'){
   $flag = $DB->Del("biz_active","ID=".$_GET["id"]);
   if($flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
}
$lists = array();
$condition = "LEFT JOIN active as a ON b.Active_ID=a.Active_ID WHERE b.Users_ID = '{$UsersID}' AND b.Biz_ID={$BizID} ORDER BY ID DESC";
$result = $DB->getPages("biz_active as b","a.Type_ID,a.Active_Name,a.starttime,a.stoptime,b.*",$condition,10);
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
        content: '/biz/active/active_view.php?id='+id
    });
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
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="./orders.php">我的活动</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <div class="control_btn">
      <!--<a href="active_add.php" class="btn_green btn_w_120">申请参加活动</a>-->
      </div>
      <table align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="48px" align="center"><strong>活动ID</strong></td>
            <td width="48px" align="center"><strong>活动名称</strong></td>
            <td width="48px" align="center"><strong>活动期限</strong></td>
            <td width="48px" align="center"><strong>推荐产品</strong></td>
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
            <td nowrap="nowrap"><?php echo date("Y-m-d",$v["starttime"]); ?> 至 <?php echo date("Y-m-d",$v["stoptime"]); ?></td>
            <td><a href="#" onclick="view(<?=$v["ID"]?>)">查看</a></td>
            <td nowrap="nowrap"><?=$Status[$v["Status"]]; ?></td>
            <td class="last" nowrap="nowrap">
            	<?php if($v['Status']<4){?>
            	<a href="active_edit.php?id=<?php echo $v["ID"]; ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a>&nbsp;&nbsp;
				<?php } ?>
              <a href="?action=del&id=<?php echo $v["ID"]; ?>"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a>
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
<script>
$(document).ready(function(){
      //设置删除的ajax
      $('.onclik').click(function(){
        //获取服务id
        var id=$(this).attr('activeid').val();
        if(confirm('您确定删除此服务')){   
          //发送ajax
          $.get("active.php",{Active_ID:id,action:'del'},function(data){
            }, 'json');
          $(this).parents('tr').remove();
        }   
      })
});
    
</script>
</body>
</html>
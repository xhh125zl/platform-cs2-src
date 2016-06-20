<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
$VotesID=empty($_GET['VotesID'])?0:$_GET['VotesID'];
$rsVotes = $DB->GetRs("votes","*","where Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$VotesID);
if(!$rsVotes){
	echo "该投票不存在";
	exit;
}
if(isset($_GET["action"]))
{
	$action=empty($_GET["action"])?"":$_GET["action"];
	if($action=="del")
	{
		$item = $DB->GetRs("votes_item","*","where Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$VotesID." and Item_ID=".$_GET["ItemID"]);
		if($item){
			//开始事务定义
			$Flag=true;
			$msg="";
			mysql_query("begin");
			$Del=$DB->Del("votes_item","Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$VotesID." and Item_ID=".$_GET["ItemID"]);
			$Flag=$Flag&&$Del;
			$Data = array(
				"Votes_Votes"=>($rsVotes["Votes_Votes"]-$item["Item_Votes"])>=0 ?  ($rsVotes["Votes_Votes"]-$item["Item_Votes"]) : 0  
			);
			$Set=$DB->Set("votes",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$_GET["VotesID"]);
			$Flag=$Flag&&$Del;
			if($Flag){
				mysql_query("commit");
				echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
			}else{
				mysql_query("roolback");
				echo '<script language="javascript">alert("保存失败");history.go(-1);</script>';
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
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/votes.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/votes.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="votemanage.php">投票管理</a></li>
      </ul>
    </div>
    <div id="reserve" class="r_con_wrap">
      <div class="control_btn"><a href="items_add.php?VotesID=<?php echo $VotesID;?>" class="btn_green btn_w_120">添加选项</a><a href="javascript:void(0);" class="btn_gray" onClick="history.go(-1);">返 回</a></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
         <?php if($rsVotes["Votes_Pattern"]==0){?>
          <tr>
            <td width="12%">序号</td>
            <td width="30%">投票选项</td>
            <td width="20%">票数</td>
            <td width="26%">图片</td>
            <td width="12%" class="last">操作</td>
          </tr>
          <?php }else{?>
          <tr>
            <td width="12%">序号</td>
            <td width="40%">投票选项</td>
            <td width="36%">票数</td>
            <td width="12%" class="last">操作</td>
          </tr>
          <?php }?>
        </thead>
        <tbody>
          <?php $DB->getPage("votes_item","*","where Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$VotesID." order by Item_Sorts asc,Item_ID asc",$pageSize=10);
		  $i=1;
		  while($rsItem=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td nowrap="nowrap"><?php echo $rsItem["Item_Title"] ?></td>
            <td nowrap="nowrap"><?php echo $rsItem["Item_Votes"] ?></td>
            <?php if($rsVotes["Votes_Pattern"]==0){?>
            <td nowrap="nowrap"><img src="<?php echo $rsItem["Item_ImgPath"] ?>" width="100" /></td> 
            <?php }?>          
            <td nowrap="nowrap" class="last">
            	<a href="items_edit.php?VotesID=<?php echo $VotesID ?>&ItemID=<?php echo $rsItem["Item_ID"];?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> 
                <a href="?action=del&VotesID=<?php echo $VotesID ?>&ItemID=<?php echo $rsItem["Item_ID"];?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a>
            </td>
          </tr>
          <?php $i++;
		  }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>
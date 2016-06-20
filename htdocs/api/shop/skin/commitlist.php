<?php require_once('skin/top.php'); ?>
  <link href='/static/api/shop/skin/default/css/commit.css' rel='stylesheet' type='text/css' />
  <div class="commit_title"><a href="<?php echo $shop_url;?>products/<?php echo $ProductsID;?>/"><?php echo $rsProducts["Products_Name"];?></a></div>
  <div class="commit_list">
   <?php
	  $commit = $DB->GetRs("user_order_commit","count(*) as num, sum(Score) as score","where Status=1 and Product_ID=".$ProductsID);
	  $num = $commit["num"];
	  $score = $commit["score"];
	  $average = $num==0 ? '' : '( <font style="color:#7d0000; font-size:16px; font-weight:bold; font-family:Times New Roman, Times, serif;">'.number_format(($score/$num), 1, '.', '').'</font> 分)';
	?>
   <h2>共有 <font style="color:#F60; font-size:14px;"><?php echo $num;?></font> 条评论<?php echo $average;?></h2>
   <?php
      $DB->getPage("user_order_commit","*","where Status=1 and Product_ID=".$ProductsID." order by CreateTime DESC",$pageSize=20);
	  while($v=$DB->fetch_assoc()){
   ?>
   <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
     <td class="commit_time"><?php echo date("Y-m-d H:i:s",$v["CreateTime"]);?></td>
    </tr>
    <tr>
     <td class="commit_note"><?php echo $v["Note"];?></td>
    </tr>
    <tr>
     <td class="commit_score"><?php echo number_format(($v["Score"]), 1, '.', '');?> 分</td>
    </tr>
   </table>
   <?php }?>
  </div>
  <?php $DB->showWechatPage('/api/'.$UsersID.'/shop/commit/'.$ProductsID.'/'); ?>
<?php require_once('skin/distribute_footer.php'); ?>
</body>
</html>
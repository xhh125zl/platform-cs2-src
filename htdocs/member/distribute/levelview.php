<?php
/*edit in 20160322*/
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
//删除级别
if(empty($_REQUEST['ProductsID'])){
	echo '缺少必要的参数';
	exit;
}

$ProductsID=empty($_REQUEST['ProductsID'])?0:$_REQUEST['ProductsID'];
$rsProducts = $DB->GetRs("shop_Products","*","where Users_ID='".$_SESSION["Users_ID"]."' and Products_ID=".$ProductsID);
$distribute_list = $rsProducts['Products_Distributes'] ? json_decode($rsProducts['Products_Distributes'],true) : array(); //分佣金额列表
$dis_config = dis_config($_SESSION["Users_ID"]);
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
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/distribute/dis_level.js'></script>    
    <div id="orders" class="r_con_wrap">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="20%" nowrap="nowrap">级别名称</td>           
            <td width="70%" nowrap="nowrap">佣金明细</td>            
          </tr>
        </thead>
        <tbody>
         <?php
		 $DB->Get("distribute_level","Level_ID,Users_ID,Level_Name","where Users_ID='".$_SESSION["Users_ID"]."'");
			while($dislevelarr=$DB->fetch_assoc()){
			  $dislevelarrs[] = $dislevelarr;			 
		  }	
		  $n = 0;
			foreach($dislevelarrs as $key=>$disinfo){
		$n++;				
		 ?>
         <tr>
            <td nowrap="nowrap"><?=$n?></td>
            <td nowrap="nowrap"><?=$disinfo['Level_Name']?></td>
            <td nowrap="nowrap">
			<?php $arr = array('一','二','三','四','五','六','七','八','九','十');
						$level =  $dis_config['Dis_Self_Bonus']?$dis_config['Dis_Level']+1:$dis_config['Dis_Level'];
						for($i=0;$i<$level;$i++){?>
							<li>
                            <?php if($dis_config['Dis_Self_Bonus']==1 && $i==$dis_config['Dis_Level']){?>
                            <strong>自销佣金</strong>
                            <?php }else{?>                            
							<strong><?=$arr[$i]?>级</strong>
                            <?php }?>                           
								<?=!empty($distribute_list[$disinfo['Level_ID']][$i])?$distribute_list[$disinfo['Level_ID']][$i]:0?>%&nbsp;(佣金比例的百分比)<em></em></li>
								<?php }?>
							
			</td>            
          </tr>
         <?php }?>
        </tbody>
      </table>      
    </div>
</body>
</html>
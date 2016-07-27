<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$result = $DB->getPages("pintuan_aword","*","WHERE 1=1",10);
$list = $DB->toArray();
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet'
	type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>
<body>
	<div id="iframe_page">
		<div class="iframe_content">
			<link href='/static/member/css/shop.css' rel='stylesheet'
				type='text/css' />
			<script type='text/javascript' src='/static/member/js/shop.js'></script>
			<?php include 'top.php'; ?>
			<link href='/static/js/plugin/operamasks/operamasks-ui.css'
				rel='stylesheet' type='text/css' />
			<script type='text/javascript'
				src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
			<link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet'
				type='text/css' />
			<script type='text/javascript'
				src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
			<script language="javascript">$(document).ready(shop_obj.orders_init);</script>

			<div id="orders" class="r_con_wrap">
				<form id="submit_form" method="get" action="">
					<table border="0" cellpadding="5" cellspacing="0"
						class="r_con_table" id="order_list">
						<thead>
							<tr>
								<td width="2%" nowrap="nowrap">序号</td>
								<td width="5%" nowrap="nowrap">抽奖团数</td>
								<td width="5%" nowrap="nowrap">抽奖订单数</td>
								<td width="3%" nowrap="nowrap">商品数</td>
								<td width="4%" nowrap="nowrap">总中奖数</td>
								<td width="4%" nowrap="nowrap">总未中奖数</td>
								<td width="4%" nowrap="nowrap">抽奖时间</td>
								<td width="11%" nowrap="nowrap">其他</td>
							</tr>
						</thead>
						<tbody>
              <?php
              foreach($list as $k => $v)
              {
              ?> 
                <tr>
                <td width="2%" nowrap="nowrap"><?=$v['Aword_ID']?></td>
								<td width="5%" nowrap="nowrap"><?=$v['teamTotal']?></td>
								<td width="5%" nowrap="nowrap"><?=$v['orderTotal']?></td>
								<td width="3%" nowrap="nowrap"><?=$v['goodsTotal']?></td>
								<td width="4%" nowrap="nowrap"><?=substr_count($v['awordTeamlist'],",")+1 ?></td>
								<td width="4%" nowrap="nowrap"><?=substr_count($v['noneAwordTeamlist'],",")+1 ?></td>
								<td width="4%" nowrap="nowrap"><?=date("Y-m-d H:i",$v['addtime'])?></td>
								<td width="11%" nowrap="nowrap">
                  <?php
                      $goodsConfig = json_decode($v['goodsConfig'],true);
                      foreach($goodsConfig['list'] as $key => $value)
                      {
                          $goodsInfo = $DB->GetRs("pintuan_products","Products_Name","WHERE Products_ID={$value['id']}");
                          if(!empty($goodsInfo)){
                              echo "商品【{$goodsInfo['Products_Name']}】  中奖团数【".(substr_count($value['awordlist'],",")+1)."】"."    未中奖团数【".(substr_count($value['noneAwordlist'],",")+1)."】";
                          }
                      }
								  ?>
								</td>
								</tr>
              <? } ?>
             </tbody>
					</table>
				</form>
				<div class="blank20"></div>
        <?php $DB->showPage(); ?>
        </div>
		</div>
	</div>
</body>
</html>
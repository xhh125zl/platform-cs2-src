<?php require_once('top.php'); ?>
<body>
<script type='text/javascript' src='/wap/js/reserve.js?t=<?php echo time();?>'></script>
<script language="javascript">$(document).ready(reserve_obj.reserve_init);</script>
<link href='/wap/css/sjrz.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<div id="reserve_success">提交成功！</div>
<div id="shop_page_contents">
	<div id="cover_layer"></div>
    <!--header-->
 	<div id="header_common">
  		<div class="remark"><span onclick="history.go(-1);"></span>商家入驻</div>
  		<div class="clear"></div>
 	</div>
	<div id="reserve">
	  <form name="reserve_form">
		<div class="reserve_table">
		  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			<thead>
			  <tr>
				<td colspan="2">请认真填写表单</td>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td class="label">商家名称</td>
				<td><input type="text" name="company" value="" class="form_input" notnull /></td>
			  </tr>
			  <tr>
				<td class="label">行业分类</td>
				<td>
					<select id="trade_0">
					 <option value="">请选择分类</option>
					 <?php
						$lists = array();
						$DB->get("industry","*","where parentid=0 order by id asc");
						while($r=$DB->fetch_assoc()){
							$lists[] = $r;
						}
						foreach($lists as $t){
							echo '<option value="'.$t["id"].'">'.$t["name"].'</option>';
						}
					 ?>
					</select>
					<select id="trade_1" name="trade" notnull>
					 <option value="">请选择分类</option>
					</select>
				</td>
			  </tr>
			  <tr>
				<td class="label">联系人</td>
				<td><input type="text" name="contact" value="" class="form_input" notnull /></td>
			  </tr>
			  <tr>
				<td class="label">联系电话</td>
				<td><input type="text" name="telephone" value="" class="form_input" /></td>
			  </tr>
			  <tr>
				<td class="label">联系手机</td>
				<td><input type="text" name="mobile" value="" pattern="[0-9]*" class="form_input" notnull /></td>
			  </tr>
			  <tr>
				<td class="label">邮箱</td>
				<td><input type="text" name="email" value="" class="form_input" /></td>
			  </tr>
			</tbody>
		  </table>
		</div>
		<div class="blank9"></div>
		<div>
		  <input type="button" class="submit" value="提 交" />
		</div>
	  </form>
	 </div>
	<div id="footer_points"></div>
	<!--页脚导航 begin-->
	<?php
	require_once("footer.php");
	?>
	<!--页脚导航 end-->
</div>
</body>
</html>

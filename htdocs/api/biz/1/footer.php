 <div id="footer_points"></div>
 <footer id="footer">
  <ul>
   <li class="first"><a href="<?php echo $biz_url;?>">店铺首页</a></li>
   <li><a href="<?php echo $biz_url;?>allcate/">全部分类</a></li>
   <li><a href="<?php echo $biz_url;?>products/0/">全部产品</a></li>
   <li><a href="<?php echo $biz_url;?>intro/">店铺简介</a></li>
  </ul>
 </footer>
<script type="text/javascript">
$('#header_biz .back').click(function(){
	history.go(-1);
});
$('#header_biz .more').click(function(){
	var display = $('#header_biz ul').css('display');
	if(display=='none'){
		$('#header_biz ul').css('display','block');
	}else{
		$('#header_biz ul').css('display','none');
	}
});
</script>
<?php if($rsBiz["Biz_Kfcode"]){
	echo htmlspecialchars_decode($rsBiz["Biz_Kfcode"],ENT_QUOTES);
}?>
</body>
</html>
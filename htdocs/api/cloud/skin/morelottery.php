<?php 
if(!empty($_POST) && $_POST['action'] == 'gogo') {
	$rsDetail = $DB->GetRS('cloud_products_detail','Cloud_Detail_ID','where qishu='.intval($_POST['qishu']).' and Products_ID='.$ProductsID);
	if(!empty($rsDetail)){
		$Data2 = array(
		    'status'=>1,
			'id'=>$rsDetail['Cloud_Detail_ID'],
		);
	}else{
		$Data2 = array(
		    'status'=>0,
			'msg'=>'数据不存在！',
		);
	}
	echo json_encode($Data2, JSON_UNESCAPED_UNICODE);
	exit;
}
?>
<?php require_once('top.php'); ?>
<body id="loadingPicBlock" class="g-acc-bg">
<link href="/static/api/cloud/css/goodsrecords.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<style>
@-moz-document url-prefix() {    .m-search .f-search-text input {        height:22px;  line-height:22px; width: 90px;   }}
</style>
<div class="g-winner-con clearfix">
	<div class="m-search gray9"> <span class="fl">直达第</span> <span class="fr"><a id="btnGo" href="javascript:;">云<i class="z-set"></i></a></span>
		<div class="f-search-text">
			<input id="txtPeriod" value="" maxlength="8" type="text" placeholder="请输入数字" />
		</div>
	</div>
	<div class="m-win-list clearfix">
		<ul id="winList">
			<?php foreach($cloud_products_detail_list as $val){
				$User_Info = unserialize($val['User_Info']);
			?>
			<li onclick="location.href='<?php echo $cloud_url.'/lottery/'.$val['Cloud_Detail_ID'].'/'?>'"><cite>第<?php echo $val['qishu'];?>云</cite>
				<dl class="gray9">
					<dt><img src="<?php echo $val['User_HeadImg'];?>"></dt>
					<dd class="win-name"><a href="javascript:;" class="blue"><?php echo $val['User_NickName'];?></a></dd>
					<dd class="z-font-size">幸运码：<em class="orange"><?php echo $val['Luck_Sn'];?></em></dd>
					<dd class="z-font-size"> 参与人次：<em class="orange"><?php echo $User_Info[2];?></em></dd>
					<?php 
						if(strpos($val['Products_End_Time'], '.')){
							list($usec, $sec) = explode('.', $val['Products_End_Time']);
							$date = date('Y-m-d H:i:s', $usec);
						}else{
							$date = date('Y-m-d H:i:s', $val['Products_End_Time']);
							$sec = 0;
						}
					?>
					<dd class="colorbbb" style="display:none;"><?php echo $date.'.'.$sec;?></dd>
				</dl>
			</li>
			<?php }?> 
		</ul>
	</div>
</div>
<script>
    $('#btnGo').click(function(){
		if(isNaN($('#txtPeriod').val()) || $('#txtPeriod').val() == ''){
			alert('请输入数字');
			return;
		}
		$.post('?', {action:'gogo',qishu:$('#txtPeriod').val()},function(data){
			if(data.status == 1){
				location.href = "<?php echo $cloud_url;?>lottery/"+data.id+"/";
			}else{
				alert(data.msg);
			}
		}, 'json');
		
	});
</script>
</body>
</html>
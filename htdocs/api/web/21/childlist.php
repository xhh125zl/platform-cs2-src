<?php require_once('header.php');?>
<div id="web_page_contents">
  <div class="wrap" id="columnchild">
  <?php if($rsColumn["Column_ChildTypeID"]==0){?>
   <div class="list-type-0">
    <?php
	$DB->Get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=".$ColumnID." order by Column_Index asc,Column_ID desc");
	while($rsChild=$DB->fetch_assoc()){
		echo '<a href="'.(empty($rsChild["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsChild["Column_ID"].'/':$rsChild["Column_LinkUrl"]).'">
				<div class="item">';
		echo $rsChild["Column_ImgPath"] ? '<div class="img"><img src="'.$rsChild["Column_ImgPath"].'"></div>' : '';
		echo '<div class="info"><h2>'.$rsChild["Column_Name"].'</h2></div><div class="detail"><span></span></div>
				</div>
			</a>';
	}
	?>
	</div>
  <?php }elseif($rsColumn["Column_ChildTypeID"]==1){?>
   <div class="list-type-1">
	<div class="list">
    <?php
	$DB->Get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=".$ColumnID." order by Column_Index asc,Column_ID desc");
	while($rsChild=$DB->fetch_assoc()){
		echo '<a href="'.(empty($rsChild["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsChild["Column_ID"].'/':$rsChild["Column_LinkUrl"]).'">
			<div class="item">
				<div>
					<ul>
						<li class="img"><img src="'.$rsChild["Column_ImgPath"].'"></li>
						<li class="title">'.$rsChild["Column_Name"].'</li>
					</ul>
				</div>
			</div>
		</a>';
	}
	?>
		<div class="clear"></div>
	</div>	
   </div>
  <?php }?>
   <input type="hidden" name="ShareTitle" value="<?php echo $rsColumn["Column_Name"] ?>" />
  </div>
</div>
<div id="plug_menu">
	<div class="bgcolor menu"><span class="close"></span></div>
     <?php $DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc",4);
		$i=1;
		while($rsColumn=$DB->fetch_assoc()){
			echo '<div class="bgcolor plug-btn plug-btn'.$i.' close"><a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'"><img src="'.$rsColumn["Column_ImgPath"].'" align="absmiddle" /></a></div>';
			$i++;
		}
	?>
</div>
<script type="text/javascript">
$(function(){
	$('#plug_menu .menu span').click(function(){
	    if($(this).attr('class')=='open'){
            $(this).removeClass('open').addClass('close');
            $('.plug-btn').removeClass('open').addClass('close');
	    }else{
            $(this).removeClass('close').addClass('open');
            $('.plug-btn').removeClass('close').addClass('open');
	    }
	});
	$('#plug_menu .plug-btn a').click(function(){
		$('#plug_menu .menu span').click();
	});
});
</script>
<div class="blank15"></div>
<script language="javascript">$(document).ready(function(){$('#support').css('bottom', 0);});</script>
<?php require_once('footer.php');?>
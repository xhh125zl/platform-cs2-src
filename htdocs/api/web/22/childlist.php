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
	<div>
        <div class="menu"><img src="/static/api/web/skin/22/menu.png" /></div>
        <ul>
        <?php $DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc");
		while($rsColumn=$DB->fetch_assoc()){
			echo '<li><a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'"><div class="img"><img src="'.$rsColumn["Column_ImgPath"].'" /></div><label>'.$rsColumn["Column_Name"].'</label></a></li>';
		}
	?>
     </ul>
    </div>
</div>
<div id="cover"></div>
<script type="text/javascript">
$(function(){
	$('#plug_menu .menu img').click(function(){
	    if($("#plug_menu ul").css('display')=='none'){
            $("#plug_menu ul, #cover").show();
	    }else{
            $("#plug_menu ul, #cover").hide();
	    }
	});
	
	$('#plug_menu ul li a, #cover').click(function(){
		$("#plug_menu ul, #cover").hide();
	});
});
</script>

<div class="blank15"></div>
<script language="javascript">$(document).ready(function(){$('#support').css('bottom', 0);});</script>
<?php require_once('footer.php');?>
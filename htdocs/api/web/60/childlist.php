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
<div id="footer_points"></div>
<footer id="footer">
	<ul>
	 <li class="footer01"><a href="/api/<?php echo $UsersID;?>/reserve/">在线预约</a></li><li class="footer02"><a href="tel:<?php echo $rsConfig['CallPhoneNumber'];?>">客服热线</a></li><li class="footer03"><a href="/api/<?php echo $UsersID ?>/web/lbs/">导航</a></li><div class="clear"></div>
	</ul>
</footer>
<?php require_once('footer.php');?>
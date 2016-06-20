<?php require_once('header.php');?>
<div id="web_page_contents">
  <div class="wrap" id="column">
    <?php
	  $column_arr = array();
	  $column_arr[] = $ColumnID;
      $DB->Get("web_column","Column_ID","where Users_ID='".$UsersID."' and Column_ParentID=".$ColumnID);
	  while($r=$DB->fetch_assoc()){
		$column_arr[] = $r["Column_ID"];
	  }
	  $columnids = implode(",",$column_arr);
    ?>
    <?php
	$DB->Get("web_article","*","where Users_ID='".$UsersID."' and Column_ID in(".$columnids.") order by Article_Index asc,Article_CreateTime desc");
if($DB->num_rows()){	
	if($rsColumn["Column_ListTypeID"]==0){
		echo '<div class="list-type-0">';
		while($rsArticle=$DB->fetch_assoc()){
			echo '<a href="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">
				<div class="item">
					<div class="img"><img src="'.$rsArticle["Article_ImgPath"].'"></div>
					<div class="info">
						<h1>'.$rsArticle["Article_Title"].'</h1>
						<h2>'.$rsArticle["Article_BriefDescription"].'</h2>
					</div>
					<div class="detail">
						<span></span>
					</div>
				</div>
			</a>';
		}
		echo '</div>';
	}elseif($rsColumn["Column_ListTypeID"]==1){
		echo '<div class="list-type-1">
			<div class="list">';
		while($rsArticle=$DB->fetch_assoc()){
			echo '<a href="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">
				<div class="item">
					<div>
						<ul>
							<li class="img"><img src="'.$rsArticle["Article_ImgPath"].'"></li>
							<li class="title">'.$rsArticle["Article_Title"].'</li>
						</ul>
					</div>
				</div>
			</a>';
		}
		echo '<div class="clear"></div>
			</div>
		</div>';
	}elseif($rsColumn["Column_ListTypeID"]==2){
		echo '<div class="list-type-2">';
		while($rsArticle=$DB->fetch_assoc()){
			echo '<a href="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">
				<div class="item">
					<div class="info">
						<h1>'.$rsArticle["Article_Title"].'</h1>
						<h2>'.$rsArticle["Article_BriefDescription"].'</h2>
					</div>
					<div class="detail">
						<span></span>
					</div>
				</div>
			</a>';
		}
		echo '</div>';
	}
}else{
	echo '<div class="contents">'.$rsColumn["Column_Description"].'</div>';
}
?>
    <input type="hidden" name="ShareTitle" value="<?php echo $rsColumn["Column_Name"] ?>" />
  </div>
</div>
<div id="footer_points"></div>
<footer id="footer">
	<ul>
     <?php
				$DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 order by Column_Index asc",4);
				while($rsColumn=$DB->fetch_assoc()){
					echo '<li><a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'">'.$rsColumn["Column_Name"].'</a></li>';
			}?>		
			        </ul>
</footer>
<?php require_once('footer.php');?>
<?php
$setting_flag = false;
//判断是否设定域名
$pcConfig = $DB->GetRs('pc_setting', '*', 'where Users_ID="'.$_SESSION['Users_ID'].'" and module="shop"');
if(!empty($pcConfig['site_url'])){
    $setting_flag = true;
}
?>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="20%" nowrap="nowrap">名称</td>
            <td width="60%" nowrap="nowrap" class="last">Url</td>
          </tr>
        </thead>
        <tbody>
		  
          <?php
				$ii=0;
				$list_column = array();
				$DB->getPage("shop_products","*","where Users_ID='".$_SESSION["Users_ID"]."' and Products_Status=1 order by Products_Index asc",40);
				while($r=$DB->fetch_assoc()){
					$list_column[] = $r;
				}
				foreach($list_column as $k=>$v){
					$ii++;
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $ii;?></td>
            <td nowrap="nowrap"><?php echo $v["Products_Name"];?></td>
            <td nowrap="nowrap" class="left last">
			<?php if(!$setting_flag){?>
            	http://<?php echo $_SERVER["HTTP_HOST"] ?>/pc.php/shop/goods/index/UsersID/<?php echo $_SESSION["Users_ID"] ?>/id/<?php echo $v["Products_ID"];?>
			<?php }else{?>
				http://<?php echo $pcConfig['site_url'] ?>/pc.php/shop/goods/index/id/<?php echo $v["Products_ID"];?>
			<?php }?>
            </td>
          </tr>
          <?php
		  		}
		  ?>
        </tbody>
      </table>
	  <div class="blank20"></div>
      <?php $DB->showPage(); ?>
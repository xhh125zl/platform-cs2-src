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
          <tr>
            <td nowrap="nowrap">1</td>
            <td nowrap="nowrap">商城首页</td>
            <td nowrap="nowrap" class="left last">
			<?php if($setting_flag){?>
			http://<?php echo trim($pcConfig['site_url'], '/'); ?>/pc.php
			<?php }else{?>
			http://<?php echo $_SERVER["HTTP_HOST"] ?>/pc.php/shop/index/index/UsersID/<?php echo $_SESSION["Users_ID"]?>
			<?php }?>
			</td>
          </tr>
          <?php
				$list_column = array();
				$i = 2;
				$DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Category_ID asc");
				while($r=$DB->fetch_assoc()){
					$list_column[] = $r;
				}
				foreach($list_column as $k=>$v){
					$i++;
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $i;?></td>
            <td nowrap="nowrap"><?php echo $v["Category_Name"];?></td>
            <td nowrap="nowrap" class="left last">
			<?php if($setting_flag){?>
			http://<?php echo $pcConfig['site_url'] ?>/pc.php/shop/list/index/id/<?php echo $v["Category_ID"];?>
			<?php }else{?>
			http://<?php echo $_SERVER["HTTP_HOST"] ?>/pc.php/shop/list/index/UsersID/<?php echo $_SESSION["Users_ID"] ?>/id/<?php echo $v["Category_ID"];?>
			<?php }?>
            </td>
          </tr>
          <?php
		  	}
		  ?>
        </tbody>
      </table>
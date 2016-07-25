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
            <td nowrap="nowrap"><?php echo 1;?></td>
            <td nowrap="nowrap">拼团首页</td>
            <td nowrap="nowrap" class="left last">
            	http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/pintuan/
            </td>
          </tr>
          <?php
		        $ii = 0;
				$list_column = array();
				$DB->getPage("pifa_products","*","where Users_ID='".$_SESSION["Users_ID"]."'",40);
				while($r=$DB->fetch_assoc()){
					$list_column[] = $r;
				}
				foreach($list_column as $k=>$v){
					$ii++;
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $ii+1;?></td>
            <td nowrap="nowrap"><?php echo $v["Products_Name"];?></td>
            <td nowrap="nowrap" class="left last">
            	http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/pintuan/product/<?php echo $v["Products_ID"];?>/
            </td>
          </tr>
          <?php
		  		}
		  ?>
        </tbody>
      </table>
	  <div class="blank20"></div>
      <?php $DB->showPage(); ?>
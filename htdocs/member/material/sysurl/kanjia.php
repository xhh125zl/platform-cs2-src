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
            <td nowrap="nowrap">首页</td>
            <td nowrap="nowrap" class="left last">
            	http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/kanjia/
            </td>
          </tr>
          <?php
				$list = array();
				$i = 1;
				$DB->get("kanjia","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Kanjia_ID asc");
				while($r=$DB->fetch_assoc()){
					$list[] = $r;
				}
				foreach($list as $k=>$v){
					$i++;
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $i;?></td>
            <td nowrap="nowrap"><?php echo $v["Kanjia_Name"];?></td>
            <td nowrap="nowrap" class="left last">
            	http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/kanjia/activity/<?php echo $v["Kanjia_ID"];?>/
            </td>
          </tr>
          <?php
		  		}
		  ?>
        </tbody>
      </table>
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
            	http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/votes/
            </td>
          </tr>
		  <?php
				$list_column = array();
				$j = 1;
				$DB->getPage("votes","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Votes_ID desc",40);
				while($r=$DB->fetch_assoc()){
					$list_column[] = $r;
				}
				foreach($list_column as $k=>$v){
					$j++;
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $j;?></td>
            <td nowrap="nowrap"><?php echo $v["Votes_Title"];?></td>
            <td nowrap="nowrap" class="left last">
            	http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"];?>/votes/<?php echo $v["Votes_ID"];?>/
            </td>
          </tr>
          <?php
		  		}
		  ?>
        </tbody>
      </table>
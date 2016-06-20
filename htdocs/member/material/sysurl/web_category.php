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
            <td nowrap="nowrap">0</td>
            <td nowrap="nowrap">首页</td>
            <td nowrap="nowrap" class="left last">http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/web/</td>
          </tr>
          <?php
				$lists = array();
				$DB->get("web_column","Column_ID,Column_Name,Column_ParentID","where Users_ID='".$_SESSION["Users_ID"]."' order by Column_ParentID asc,Column_Index asc,Column_ID asc");
				while($r=$DB->fetch_assoc()){
					if($r["Column_ParentID"] == 0){
						$lists[$r["Column_ID"]] = $r;
					}else{
						if($r["Column_ParentID"]<>$r["Column_ID"]){
							$lists[$r["Column_ParentID"]]["child"][] = $r;
						}
					}
				}
				$i = 0;
				foreach($lists as $k=>$v){
					$i++;
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $i;?></td>
            <td nowrap="nowrap"><?php echo $v["Column_Name"];?></td>
            <td nowrap="nowrap" class="left last">
            	http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/web/column/<?php echo $v["Column_ID"];?>/
            </td>
          </tr>
          <?php
          	if(!empty($v["child"])){
				foreach($v["child"] as $key=>$value){
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $i.'.'.($key+1);?></td>
            <td nowrap="nowrap"><?php echo $value["Column_Name"];?></td>
            <td nowrap="nowrap" class="left last">
            	http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/web/column/<?php echo $value["Column_ID"];?>/
            </td>
          </tr>
		  <?php	}
			}
		  ?>
          <?php
		  		}
		  ?>
        </tbody>
      </table>
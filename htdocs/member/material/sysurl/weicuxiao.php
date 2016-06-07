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
            <td nowrap="nowrap">刮刮卡</td>
            <td nowrap="nowrap" class="left last">http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/scratch/</td>
          </tr>
          <tr>
            <td nowrap="nowrap">2</td>
            <td nowrap="nowrap">水果达人</td>
            <td nowrap="nowrap" class="left last">http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/fruit/</td>
          </tr>
          <tr>
            <td nowrap="nowrap">3</td>
            <td nowrap="nowrap">欢乐大转盘</td>
            <td nowrap="nowrap" class="left last">http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/turntable/</td>
          </tr>
          <tr>
            <td nowrap="nowrap">4</td>
            <td nowrap="nowrap">一站到底</td>
            <td nowrap="nowrap" class="left last">
             <?php
				$list_column = array();
				$i = 0;
				$DB->get("battle","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Battle_ID asc");
				while($r=$DB->fetch_assoc()){
					$list_column[] = $r;
				}
				foreach($list_column as $k=>$v){
					$i++;
					echo $v["Battle_Title"].'<br />'.'http://'.$_SERVER["HTTP_HOST"].'/api/'.$_SESSION["Users_ID"].'/battle/'.$v["Battle_ID"].'/';
					if($i<>2){
						echo '<br />'; 
					}
				}
		     ?>
            </td>
          </tr>
		  <tr>
            <td nowrap="nowrap">5</td>
            <td nowrap="nowrap">抢红包</td>
            <td nowrap="nowrap" class="left last">http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/hongbao/</td>
          </tr>
		  <tr>
            <td nowrap="nowrap">6</td>
            <td nowrap="nowrap">微助力</td>
            <td nowrap="nowrap" class="left last">http://<?php echo $_SERVER["HTTP_HOST"] ?>/api/<?php echo $_SESSION["Users_ID"] ?>/zhuli/</td>
          </tr>
        </tbody>
      </table>
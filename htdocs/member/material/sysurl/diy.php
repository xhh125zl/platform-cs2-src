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
			$j=0;
				$list_column = array();
				$DB->getPage("wechat_url","*","where Users_ID='".$_SESSION["Users_ID"]."'",40);
				while($r=$DB->fetch_assoc()){
					$list_column[] = $r;
				}
				foreach($list_column as $k=>$v){
					$j++;
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $j;?></td>
            <td nowrap="nowrap"><?php echo $v['Url_Name'];?></td>
            <td nowrap="nowrap" class="left last">
            	<?php echo $v['Url_Value'];?>
            </td>
          </tr>
          <?php
		  		}
		  ?>
        </tbody>
        <tfoot>
         <tr>
            <td nowrap="nowrap" style="color:#FF0000">新增</td>
            <td nowrap="nowrap"><input id="Url_Name" value="" style="height:32px; line-height:32px; border:1px #dfdfdf solid; border-radius:5px" /></td>
            <td nowrap="nowrap" class="last" align="left"><div style="width:100%; text-align:left"><input id="Url_Link" value="" style="height:32px; line-height:32px; border:1px #dfdfdf solid; border-radius:5px; width:300px" />&nbsp;&nbsp;<a href="javascript:void(0);" style="padding:7px 20px; border-radius:5px; background:#1584D5; color:#FFF;" class="url_add">增加</a></div></td>
         </tr>
        </tfoot>
      </table>
      
	  <div class="blank20"></div>
      <?php $DB->showPage(); ?>
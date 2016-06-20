<div class="r_nav" id="menuset">
      <ul>
        <li id="seta1"> <a href="agent.php">代理获奖记录</a> </li>        
        <li id="seta2"><a href="agent_list.php">地区代理列表</a></li>
		<?php if($dis_config->Dis_Agent_Type == 1):?>
		<li id="seta3"><a href="agent_orders.php">地区代理申请列表</a></li> 
		<?php endif;?>
      </ul>
    </div>
	
<script type="text/javascript">
var curid = <?=$curid?>;
$("#menuset li").each(function() {    
    $(this).removeClass();
            });
    $("#seta"+curid).addClass("cur");
</script>
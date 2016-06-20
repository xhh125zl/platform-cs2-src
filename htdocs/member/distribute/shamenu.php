<div class="r_nav" id="menuset">
      <ul>        
		<li id="seta1"><a href="shareholder.php">股东分红记录</a></li>
		<li id="seta2"><a href="sha_list.php">股东列表</a></li>
		<?php if($dis_config->Sha_Agent_Type == 1):?>
		<li id="seta3"><a href="sha_orders.php">股东申请列表</a></li>
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
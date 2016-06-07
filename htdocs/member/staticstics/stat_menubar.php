<div class="r_nav">
        <ul id="menuset">
            <li id="sett1"><a href="fans.php">粉丝数据统计</a></li>
            <li id="sett2"><a href="visit.php">页面访问统计</a></li>
            <li id="sett3"><a href="sales.php">微促销参与次数</a></li>
            <li id="sett4"><a href="user.php">会员注册统计</a></li>
            <li id="sett5"><a href="user_area.php">会员来源地统计</a></li>
        </ul>
	</div>
	
<script type="text/javascript">
var curid = <?=$curid?>;
$("#menuset li").each(function() {    
    $(this).removeClass();
            });
    $("#sett"+curid).addClass("cur");
</script>
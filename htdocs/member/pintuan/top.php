<?php 
    $curModel = basename($_SERVER['REQUEST_URI']);
?>
<div class="r_nav">
	<ul>
        <li <?=stripos($curModel,'config')!==false?'class="cur"':'' ?>><a href="./config.php">基本设置</a></li>
        <li <?=stripos($curModel,'home')!==false?'class="cur"':'' ?>><a href="./home.php">首页设置</a></li>
        <li <?=stripos($curModel,'products')!==false?'class="cur"':'' ?>><a href="./products.php">产品管理</a></li>
        <li <?=stripos($curModel,'cate')!==false?'class="cur"':'' ?>><a href="./cate.php">分类管理</a></li>
        <li <?=stripos($curModel,'orders')!==false?'class="cur"':'' ?>><a href="./orders.php">订单管理</a></li>
        <li <?=stripos($curModel,'comment')!==false?'class="cur"':'' ?>><a href="./comment.php">评论管理</a></li>
        <!--<li <?=stripos($curModel,'awordConfig')!==false?'class="cur"':'' ?>><a href="./awordConfig.php">计划任务配置</a></li>
        <li <?=stripos($curModel,'aword')!==false?'class="cur"':'' ?>><a href="./aword.php">抽奖统计</a></li>-->
    </ul>
</div>
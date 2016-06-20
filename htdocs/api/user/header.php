<div id="my_header">
  <div class="face"><a href="/api/<?php echo $UsersID ?>/user/my/"></a></div>
  <ul>
    <li><?php echo $rsUser["User_Name"] ?>【<?php echo $UserLevel[$rsUser["User_Level"]]["Name"] ?>】</li>
    <!--<li>余额:￥1.25</li>-->
    <li>现有积分: <?php echo $rsUser["User_Integral"] ?>分</li>
    <li>总获积分: <?php echo $rsUser["User_TotalIntegral"] ?>分</li>
  </ul>
</div>

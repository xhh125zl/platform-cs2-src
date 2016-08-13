<?php 
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
    $teamid = $_GET['teamid'];
    
    $sql = "select * from pintuan_team t left join user u on t.userid=u.user_id left join pintuan_products p on p.products_id=t.productid where t.id=$teamid order by t.addtime desc";
    $result = $DB->query($sql);
    $team = $DB->fetch_assoc($result);
    $images = json_decode(htmlspecialchars_decode($team["Products_JSON"]), true)['ImgPath']['0'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo empty($team['Products_Name']) ? '团明细' : "团购-{$team['Products_Name']}"; ?></title>
    <link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script>
</head>
<body>
<div class="w">
    <div class="dingdan">
        <span class="fanhui l"><a href="<?php echo isset($_COOKIE['url_referer']) ? "javascript:location.href='{$_COOKIE['url_referer']}';" : 'javascript:history.go(-1)'; ?>"><img src="/static/api/pintuan/images/fanhui.png" width="17px" height="17px"></a></span>
        <span class="querendd l"><?php echo empty($team['Products_Name']) ? '团明细' : "团购-{$team['Products_Name']}"; ?></span>
        <div class="clear"></div>
    </div>
    <div class="chanpin1">
        <span class="l"><a href="<?php echo "/api/$UsersID/pintuan/xiangqing/{$team['productid']}/"; ?>"><img style="width:100px;height:100px;" src="<?php echo $images; ?>"></a></span>
        <span class="cp l" style="width:45%;">
            <ul>
                <li><strong><?php echo $team['Products_Name']; ?></strong></li>
                <li>数量：1</li>
                <li><?php echo $team['Products_BriefDescription']; ?></li>
            </ul>
        </span>
        <span class="jiage r"><?php echo $team['Products_PriceT']; ?>/件</span>
    </div>
    <div class="pintuan">
        <div class="pint">
            <div class="bjt">
                <div class="bjt-img"><img style="width:50px;height:50px;" src="<?php echo $team['User_HeadImg']; ?>"></div>
            </div>
            <div class="bjx">
                <div class="bjxt">
                    <span class="l"><?php echo empty($team['User_NickName']) ? $team['User_Mobile'] : $team['User_NickName']; ?></span>
                    <?php $num = $team['people_num'] - $team['teamnum'];
                        if ($num > 0) {
                            echo "<span class='bjxt1 r'>还差{$num}人成团</span>";
                        }
                    ?>
                    <div class="clear"></div>
                    <span class="bjxt2  l"><?php echo $team['User_Area'];?></span>
                    <span class="bjxt2 r">
                    <?php 
                       $time = time();
                        if ($time < $team['stoptime']) {
                            echo date('Y-m-d', $team['stoptime']).'结束';
                        } else {
                            echo '已结束';
                        }
                    ?>
                    </span>
                </div>
            </div>
            <div class="bjtp">
                <?php if ($time >= $team['starttime'] && $time <= $team['stoptime'] && $team['teamstatus'] == 0) {
                        echo "<a onclick='tuangou()'>去参团</a>";
                    } else {
                        if($team['teamstatus']==1){
                            echo "<a>已完成</a>";   
                        }else{
                           echo '<a>已结束</a>';
                        }
                    }
                 ?>
            </div>
        </div>
        <div class="clear"></div>
        <div class="chengyuan">
            <ul>
                <?php $sql1 = "select * from pintuan_teamdetail td left join user u on td.userid=u.user_id where td.teamid = $teamid order by td.addtime;";
                    $result1 = $DB->query($sql1);
                    $teamdetails = array();
                    while ($res = $DB->fetch_assoc($result1)) {
                        $teamdetails[] = $res;
                    }
                    
                    foreach ($teamdetails as $teamdetail) {
                 ?>

                <li><img src="<?php echo $teamdetail['User_HeadImg']; ?>"><br><?php echo empty($teamdetail['User_NickName']) ? $teamdetail['User_Mobile'] : $teamdetail['User_NickName']; ?></li>

                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<div style="height:70px;"></div>
<div class="fl">
      <div class="fl1">
      <span class="m_p l"><a href="<?php echo "/api/$UsersID/pintuan/"; ?>">更多拼团</a></span>
      <span class="m_p1 l">
      <a>      
            <input type="button" value="分享" onclick="showdiv();" class="m_text">

      </a>
      </span>
      </div>
</div>
<div id="bj_1">
    <span class="l"> <input id="btnclose" type="button" onclick="hidediv();" value="关闭" class="gb_1"/></span>
    <span class="r">
        <img src="/static/api/pintuan/images/fx_1_02.png" width="150" height="118">
    </span>
    <div class="clear"></div>
    <p style="text-align: center">请点击右上角<br>【分享给好友】</p>
</div>
<script type="text/javascript">
    function showdiv() {
        $('#bj_1').show();
    }

    function hidediv() {
        $('#bj_1').hide();
    }

    function tuangou() {
        var goodsid = <?php echo $team['productid']; ?>;
        var orderstype = <?php echo $team['Products_Type']; ?>;
        var UsersID = "<?php echo $UsersID; ?>";
        var userid = <?php echo $UserID; ?>;
        var teamid = "<?php echo $team['id']; ?>";
        var num = '1';
        var one = 1;
        var Draw = <?php echo $team['Is_Draw']; ?>;
        var price = $('.jiage').html().replace('/件', '');
        $.post('/api/<?php echo $UsersID;?>/pintuan/ajax/',{action:"addcart",price:price,goodsid:goodsid,orderstype:orderstype,UsersID:UsersID,userid:userid,num:num,Draw:Draw,one:one,teamid:teamid},function(data){
                      if(data.status==0){
                          layer.msg(data.msg,{icon:1,time:3000},function(){
                              window.location.href="/api/<?php echo $UsersID;?>/pintuan/";
                          });
                          return false;
                      }else{
                          var id=data.id;
                          var status=data.v;
                          if(id==''){
                              layer.msg("不能参加自己开的团！",{icon:1,time:3000});
                          }else{
                            if (status==1){
                                location.href="/api/<?php echo $UsersID;?>/pintuan/vorder/"+id+"/";
                            }else if(status==0){
                               location.href="/api/<?php echo $UsersID;?>/pintuan/order/"+id+"/";
                            }
                          }
                      }
                  }, 'json');
    };
</script>
</body>
</html>
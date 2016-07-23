<?php
	require_once('comm/global.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

	//获取get值
	if(isset($_GET["UsersID"])){
	  $UsersID=$_GET["UsersID"];
	    }else{
	  echo '缺少必要的参数';
	  exit;
	  } 

    if (empty($_SESSION)) {
        header("location:/api/".$UsersID."/pintuan/");
        exit();
    }
//ajax获取评论
  if(isset($_POST['action']) && isset($_POST['action'])=='getCommit'){
      $gid = $_POST['productsid'];
      $sellorid = $_POST['Users_ID'];
      $page = $_POST['page'];
      $pagesize = $_POST['pagesize'];
      $offset = ($page-1)*$pagesize;
      //获取总数
      $result=$DB->get('pintuan_commit','*',"where Product_ID='".$gid."' and Users_ID='".$sellorid."' and Status='1' ORDER BY CreateTime desc LIMIT {$offset},{$pagesize}");
      $data = array();  
	    while ($comment=$DB->fetch_assoc($result)){
	      $comment['CreateTime']=date('Y-m-d H:i:s',$comment["CreateTime"]);
	      $comment['pingfen']=$comment["pingfen"];
	      $comment['Note']=$comment["Note"]?:"";
	      $data[] = $comment;
	    }
	    $msg = array();
	    if(!empty($data)){
          $msg = [
              'status'=>1,
              'data'=>$data,
              'page'=>$page
          ];
	    }else{
          $msg = [
              'status'=>0,
              'data'=>''
          ];
	    }
      die(json_encode($msg,JSON_UNESCAPED_UNICODE));
  }
	setcookie('product_detail', $_SERVER["REQUEST_URI"], time()+3600, '/', $_SERVER['HTTP_HOST']);
	setcookie('url_referer', $_SERVER["REQUEST_URI"], time()+3600, '/', $_SERVER['HTTP_HOST']);

	$user_id = $_SESSION[$UsersID.'User_ID'];
	$productsid = $_GET["productid"];

	$sql_product = "select * from pintuan_products where products_id = $productsid";
	$result_product = $DB->query($sql_product);
	$product_product = $DB->fetch_assoc($result_product);

	//订单类别 0实物 1虚拟(验证) 2虚拟(直冲)
	$orderstype = $product_product['Products_Type']; 
	//是否是抽奖  等于0  显示抽奖
	$Draw = $product_product['Is_Draw'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php 
			$DB->query("select Users_Account from users where Users_ID='$UsersID'");
			$Config=$DB->fetch_assoc();
			
			//商品名
			$nnn = $product_product['Products_Name'];
			//商城名
			$shopname = $Config['Users_Account'];
			echo $shopname."的拼团商城-".$nnn;
	?></title>
</head>
<link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
<link href="/static/api/pintuan/css/style.css" rel="stylesheet" type="text/css">
<link href="/static/api/pintuan/css/zhezhao.css" rel="stylesheet" type="text/css">
<script src="/static/api/pintuan/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/api/pintuan/js/scrolltopcontrol.js"></script> 
<script src="/static/api/pintuan/js/responsiveslides.min.js"></script>
<script src="/static/api/pintuan/js/slide.js"></script>
<script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script>
<body>
<div class="w">
<div class="dingdan">
<span class="fanhui l"><a href="javascript:history.go(-1);"><img src="/static/api/pintuan/images/fanhui.png" width="17px" height="17px"></a></span>
<span class="querendd l"><?php if(isset($_GET['teamid']) && $_GET['teamid']){ echo "立即参团"; }else{ echo "立即开团"; }?></span>
<div class="clear"></div>
</div>
<!-- 代码部分begin -->
  <div class="device">
    <a class="arrow-left" href="#"></a> 
    <a class="arrow-right" href="#"></a>
    <div class="swiper-container">
    	<div class="swiper-wrapper">
		<?php
			$DB->get('pintuan_products','*',"where Products_ID = '".$productsid."'");
			$arr=$DB->toArray(); 
			$patha=json_decode($arr['0']['Products_JSON'],true)['ImgPath'];
			foreach ($patha as $key => $image) {
				echo '<div class="swiper-slide"> <img src="'.$image.'"> </div>';
			}
		?>	
		</div>						   
    </div>
    <div class="pagination"></div>
  </div>
  <script src="/static/api/pintuan/js/idangerous.swiper.min.js"></script>
  <script>
  var mySwiper = new Swiper('.swiper-container',{
    pagination: '.pagination',
    loop:true,
    grabCursor: true,
    paginationClickable: true
  })
  $('.arrow-left').on('click', function(e){
    e.preventDefault()
    mySwiper.swipePrev()
  })
  $('.arrow-right').on('click', function(e){
    e.preventDefault()
    mySwiper.swipeNext()
  })
  </script>
<!-- 代码部分end -->
<div class="clear"></div>
<?php
if($Draw==0) {
    $images='<span class="cj_choujiang">抽奖</span>';
     $choujiang=isset($rsConfig['Award_rule'])?$rsConfig['Award_rule']:'';
  }else{
    $images="";
    $choujiang='';
  }
  
  $DB->get('pintuan_products','*',"where Products_ID = '".$productsid."'");
  $rsConfig=$DB->fetch_assoc();
  $baoyou=$rsConfig['Products_pinkage'];
  $day=$rsConfig['Products_refund'];
  $jia1=$rsConfig['Products_compensation'];
  if($baoyou=='1'){
        $bao='<li class="l">包邮</li>';
      }else{
        $bao='';
      }   
  if($day=='1'){
        $back='<li class="l">7天退款</li>';
      }else{
        $back='';
      } 
  if($jia1=='1'){
        $jia='<li class="l">假一赔十</li>';
      }else{
        $jia='';
      }   

    //获取累计销量
    // $DB->query("SELECT * FROM `user_order` where Users_ID='ge4dop9l07' and Order_Type='pintuan' and Order_Status='4' and Order_CartList like '%".$productsid."%'"); 
   	// //销量变量
   	// $count=0;
    // while($result=$DB->fetch_assoc()){
		  //   $num=isset(json_decode($result['Order_CartList'],true)['num'])?json_decode($result['Order_CartList'],true)["num"]:0;
		  //   $count+=$num;
    // } 
      echo '<div class="bj">
            <div class="">
            <span class="t3">¥'.$rsConfig['Products_PriceT'].'</span>
            <span class="t4"><del>¥'.$rsConfig['Products_PriceD'].'</del></span>
            '.$images.'
            <span class="t5 r">累计销量：'.$rsConfig['Products_Sales'].'件</span>
            </div>
            <div class="clear"></div>
            <div class="t6">'.$rsConfig['Products_Name'].'</div>
            <div class="t7">'.$rsConfig['Products_BriefDescription'].''.$choujiang.'
            </div>
            </div>
            <div class="fl_1">
            <ul>
            '.$bao.'
            '.$back.'
            '.$jia.'
            </ul>
            </div>
            <div class="clear"></div>
            ';
?>
<?php
		//获取活动人数
		$DB->get('pintuan_products','*',"where Products_ID = '".$productsid."'");
		$rsConfig=$DB->fetch_assoc();
		$num=$rsConfig['people_num']-1;
		if(!$num==0) {
		  echo'<div class="kt2">支付开团并邀请'.$num.'人参团，人数不足自动退款</div>';
		}
?>
<div> 
<!-- <div class="kt1">以下小伙伴正在发起团购，您可以直接参与</div> -->
<input id="onlyone" type='hidden' value="9">
<div class="clear"></div>
</div>
<!-- 评论模块 -->
<!-- 评论模块 -->
<?php
$DB->query("select pt.id,pt.productid,pt.teamnum,pt.teamstatus,pt.starttime,pt.stoptime,pp.people_num,pp.Products_Name,pp.Products_Status,pp.Team_Count,pp.Is_Draw,pp.order_process,u.User_NickName,u.User_Mobile,u.User_HeadImg 
	from pintuan_team as pt left join pintuan_products as pp on pt.productid=pp.Products_ID 
	left join `user` as u on pt.userid=u.User_ID where pt.teamstatus='0' and pt.productid='".$productsid."' and pt.users_id='".$UsersID."' LIMIT 0,3");
$list = array();
	while($res=$DB->fetch_assoc()){
		$list[]=$res;
	}
?>
		<?php if (count($list) > 0) { ?>
		<div class="kt1">以下小伙伴正在发起团购，您可以直接参与</div>
		<?php } ?>
		<?php  foreach ($list as $key =>$v) { ?>
		 <div class="clear"></div>
		 <div class="pintuan">
		 <div class="pint">
		 <div class="bjt"><div class="bjt-img"><img style="width:50px;height:50px;" src="<?php echo $v['User_HeadImg']; ?>"></div></div>
		 <div class="bjx">
		 <div class="bjxt">
		 <span class="l"><?php echo empty($v['User_NickName']) ? $v['User_Mobile'] : $v['User_NickName']; ?></span>
		 <?php 
		      $num = $v['people_num']-$v['teamnum'];
		      if ($num > 0) {
                    echo "<span class='bjxt1 r'>还差{$num}人成团</span>";
              } else {
              		echo "<span class='bjxt1 r'>已结束</span>";
              }
         ?>
		 <div class="clear"></div>
		 <span class="bjxt2 r"><?php  
            		 $pintuan_stop=date('Y-m-d',$product_product['stoptime']);
            		 $pintuan_start=date('Y-m-d',$product_product['starttime']);
            		 $time=date("Y-m-d",time());

						if ($pintuan_stop>=$time) {
							echo  date('Y-m-d', $product_product['stoptime']).'结束';
						} else {
							echo '已结束';
						}
					?></span>
		 </div>
		 </div>
		 <div class="bjtp">
		 <?php $time = date('Y-m-d', time());
		 	if ($time >= date('Y-m-d', $v['starttime']) && $time <= date('Y-m-d', $v['stoptime']) && $v['teamstatus'] == 0) { ?>
		 		<a href="<?php echo "/api/$UsersID/pintuan/teamdetail/{$v['id']}/" ;?>">去参团</a>
		 	<?php } else { ?>
		 		<a>已结束</a>
		 	<?php } ?>
		 </div>
		 </div>
		 <div class="clear"></div>
		 
		<?php } if (count($list) > 0) { ?>	
		<div class="gengduo"><a href="/api/<?php echo $UsersID;?>/pintuan/cantuan/<?php echo $productsid ;?>/">查看更多团</a></div>		
		<?php } ?>
		<?php
			$total=$DB->getRs('pintuan_commit','count(*) as total',"where Product_ID='".$productsid."' and Users_ID='".$UsersID."' and Status='1'");
			if ($total['total']>=0) {
			  	echo '<div class="pingjia">
	            <div class="pj"><span class="l">评价'.$total['total'].'条</span></div>
	            <div class="clear"></div></div>';
			}    
   ?>

<div id="commit"></div>
   <input type="hidden" name="page" value="1"/>
   <script>
   function getCommit(){
          var html="";
          var obj={
          'action':'getCommit',
          'productsid':'<?=$productsid ?>',
          'Users_ID':"<?=$UsersID  ?>",
          'pagesize':10
          };
          var page = $("input[name='page']").val();
          var count = "<?=$total['total']?:0?>";
          var presize =(page-1)*obj.pagesize;
          if(presize<=count){
            obj.page=page;
            $.post("/api/<?=$UsersID ?>/pintuan/xiangqing/"+obj.productsid+"/",obj,function(data){
            
                if(data.status==1){
                    $("#commit").empty();
                    var data=data.data;
                    for(var i=0;i<data.length;i++){
                        html+="<div class=\"pj\">";
                        html+="   <span class=\"l\">"+data[i].CreateTime+"</span>";
                        html+="   <span class=\"pjt r\">"+data[i].pingfen+"</span>";
                        html+="   <div class=\"clear\"></div>";
                        html+="   <div>"+data[i].Note+"</div>";
                        html+="</div>";
                    }
                    var totalPage = parseInt(count/obj.pagesize);
                    if(page>=totalPage+1){
                        html+="";
                    }else{
                        html+="<div class='gengduo'><a href='javascript:getCommit()'>加载更多</a></div>";
                    }
                    
                    page++;
                    $("input[name='page']").val(page);
                    $("#commit").html(html);
                }else{
                    $("#commit").html("");
                }
                
            },"json");
          }
      }
   $(function(){
      
      getCommit();
      
   });
   </script>



<div class="chanpin2">
<div id="con">
<ul id="tags">
  <li class="selectTag" style="border-right:1px #ccc solid;"><a onClick="selectTag('tagContent0',this)" 
  href="javascript:void(0)">产品详情</a> </li>
  <li ><a onClick="selectTag('tagContent1',this)" 
  href="javascript:void(0)">购买记录</a> </li>
</ul>
<div id="tagContent">
<div class="tagContent  selectTag" id="tagContent0">
		<?php
		    $DB->get('pintuan_products','*',"where Products_ID = '".$productsid."'");
		    while($rsConfig=$DB->fetch_assoc()){
		    if(!empty($rsConfig['Products_Description'])){
		    	$str=htmlspecialchars_decode($rsConfig['Products_Description']);
		        echo"<div class='jj'>".$str."</div>";  
		              }else{
		        echo'<div class="jj">
		              此商品暂无产品详情!
		        </div>';         
		              } 
		    }
		?>
</div>
<div class="tagContent " id="tagContent1">
<div class="bq2">
	<ul>
		<?php
		$DB->query("SELECT * FROM `user_order` as o left join user as u on o.User_ID=u.User_ID  where o.Users_ID='".$UsersID."' and (o.Order_Type='pintuan' or o.Order_Type='dangou') and o.Order_Status='2' and o.Order_CartList like '%".$productsid."%' ORDER BY Order_CreateTime desc LIMIT 0,5");
		$li=$DB->toArray();
		if (empty($li)) {
			echo '<li>暂时无数据</li>';
		}else{
			foreach ($li as $key => $v) {
				if ($v['User_NickName']==Null){
					//产品的数量
					$num=isset(json_decode($v['Order_CartList'],true)['num'])?json_decode($v['Order_CartList'],true)["num"]:1;
					echo '<li>
					<div class="pj2"><span class="l">匿名</span><span class="r">'.date("Y-m-d H:i:s",$v['Order_CreateTime']).'</span>
					<div class="clear"></div><div> <span class="l">'.json_decode($v['Order_CartList'],true)['ProductsName'].'</span><span class="r">件数:'.$num.'</span></div></div>
					<div class="clear"></div>
					</li>';
				}else{
					//产品的数量
					$num=isset(json_decode($v['Order_CartList'],true)['num'])?json_decode($v['Order_CartList'],true)["num"]:1;
					echo '<li>
					<div class="pj2"><span class="l">'.$v['User_NickName'].'</span><span class="r">'.date("Y-m-d H:i:s",$v['Order_CreateTime']).'</span>
					<div class="clear"></div><div> <span class="l">'.json_decode($v['Order_CartList'],true)['ProductsName'].'</span><span class="r">件数'.$num.'</span></div></div>
					<div class="clear"></div>
					</li>';
				}
			}
		}
	?>
</ul>
</div>
</div>

<div class="clear"></div>
<p>
  <script type="text/javascript">
function selectTag(showContent,selfObj){
  // 操作标签
  var tag = document.getElementById("tags").getElementsByTagName("li");
  var taglength = tag.length;
  for(i=0; i<taglength; i++){
    tag[i].className = "";
  }
  selfObj.parentNode.className = "selectTag";
  // 操作内容
  for(i=0; j=document.getElementById("tagContent"+i); i++){
    j.style.display = "none";
  }
  document.getElementById(showContent).style.display = "block";  
}
</script>
</p>
</div>
<div style="height:70px;"></div>
<div class="bottom1">
<div class="footer1">
  <ul style="margin-top: 5px;">
	<li><a href="<?php echo "/api/$UsersID/pintuan/"; ?>"><img src="/static/api/pintuan/images/552cd5641bbec_128.png" width="25px" height="25px" /><br>首页</a></li>
    <?php
		$aa=false;
		$DB->query("SELECT * FROM `pintuan_collet` where users_id='".$UsersID."'and userid ='".$user_id."'");
		$ress=$DB->toArray();
		foreach ($ress as $key => $value) {
			if($value['productid']==$productsid) {
				$aa=true;
			}
		}				
		if($aa){
			echo '<li><a href="#" id="shoucang"><img src="/static/api/pintuan/images/shoucang01.png" width="25px" height="25px" /><br>收藏</a></li>';
		}else{
			echo '<li><a href="#" id="shoucang"><img src="/static/api/pintuan/images/552cd61078c4f_128.png" width="25px" height="25px" /><br>收藏</a></li>';
		}
		$DB->query("SELECT * FROM `pintuan_products` where Products_ID = '".$productsid."' and Users_ID='".$UsersID."'");
		$goodsInfo=$DB->fetch_assoc();
    ?>    
    <li>
        <div class="buy_bj1">
            <a href="#" class="vt" id="dan">
                <input type="button" id="dangou" value="¥&nbsp;<?php echo empty($goodsInfo['Products_PriceD'])?0:$goodsInfo['Products_PriceD'];?>" class="va"/>
                <br/>
                <input type="button" id="price" value="单独购买" class="va"/>
            </a>
        </div>
        <div class="clear"></div>
    </li>
    <!-- 团购页面 -->
    <li>
        <div class="buy_bj">
            <a href="#" class="vt">
                <input type="button" id="tuangou" value="¥&nbsp;<?php echo empty($goodsInfo['Products_PriceT'])?0:$goodsInfo['Products_PriceT'];?>"  class="va"/>
                <br/>
                <input   type="button" id="prices" value="<?php echo empty($goodsInfo['people_num'])?'':$goodsInfo['people_num'];?>人团"  class="va"/>
                <!-- 单购页面 -->   
            </a>
        </div>
        <div class="clear"></div>
    </li>
  </ul>
</div>
</div> 
<!-- 修改转换js设置 -->
<script language="javascript" type="text/javascript">
//控制单购按钮是否启用   控制团购按钮是否启用
    window.onload=function(){
    	      var goodsid="<?php echo $productsid;?>";
		      var UsersID="<?php echo $UsersID;?>";
		      //获取当前 单购的商品
		      $.post('/api/<?php echo $UsersID;?>/pintuan/ajax/',{goodsid:goodsid,UsersID:UsersID,orther:'10003'},function(data){
		        var res=data.t;  
		        var wen=data.msg;
		        if(res==10001){
		        	
		        }else{
		          $('#price').val("支持单购");
		          $('#dangou').val("本产品不");
		         //解除绑定事件
		          $('#dangou').unbind('click');
		          $('#price').unbind('click');
		        }
		      },'json');
		    $.post('/api/<?php echo $UsersID;?>/pintuan/ajax/',{goodsid:goodsid,UsersID:UsersID,orther:'10004'},function(data){
		    	var res= data.t;
		    	var wen=data.msg;
		    	if(res==40001){
		    	  $('#tuangou').val("团购失效");
		          $('#prices').val("时间过期");
		           //解除绑定事件
		          $('#tuangou').unbind('click');
		          $('#prices').unbind('click');
		    	}
		    	if(res==40002){
		    	  $('#tuangou').val("团购失效");
		          $('#prices').val("时间未开始");
		           //解除绑定事件
		          $('#tuangou').unbind('click');
		          $('#prices').unbind('click');
		    	}
		    },'json');
     }

 </script>

<script type="text/javascript">
//控制收藏按钮
			$('#shoucang').click(function(){
				var UsersID="<?php echo $UsersID;?>";
				var userid="<?php echo $user_id;?>";
				var goodsid="<?php echo $productsid;?>";
				var orther="10001";
					$(this).find('img').attr('src','/static/api/pintuan/images/shoucang01.png');
			  		$.post('/api/<?php echo $UsersID;?>/pintuan/ajax/',{UsersID:UsersID,userid:userid,goodsid:goodsid,orther:orther},function(data){
			  		var res=data.id;
			  		var again=data.msg;
			  		if(again=='0'){

			  			$('#shoucang').find('img').attr('src','/static/api/pintuan/images/552cd61078c4f_128.png');
			  				
			  				// console.log('取消收藏');
			  		}else{
				  		if(res=='0'){
				  			// console.log('收藏失败了');
				  		}else{
				  			// console.log('收藏成功了');
				  		}
			  		}
			  	}, 'json');  
			})
</script>

<script>
  //单人按钮的设置
  	$('#dangou,#price').click(function(){
      		  var goodsid=<?php echo $productsid;?>;
		      var orderstype=<?php echo $orderstype;?>;
		      var UsersID = "<?php echo $UsersID;?>";
		      var userid =<?php echo $_SESSION[$UsersID.'User_ID'];?>;
		      var teamid = "<?php echo isset($_GET['teamid'])?$_GET['teamid']:0;  ?>";
		      var num='1';
		      var one=0;
		      var Draw=<?php echo empty($Draw)?0:$Draw;?>;
		      var price = $('.t4').find('del').html().replace('¥','')
           $.post('/api/<?php echo $UsersID;?>/pintuan/ajax/',{action:"addcart",goodsid:goodsid,orderstype:orderstype,UsersID:UsersID,userid:userid,num:num,Draw:Draw,one:one,teamid:teamid},function(data){
					  if(data.status==0){
						  layer.msg(data.msg,{icon:1,time:3000},function(){
                    		  window.location.href="/api/<?php echo $UsersID;?>/pintuan/";
                          });
						  return false;
					  }else{
						  var res=data.id;
					      var ss=data.v;
				          if(res==''){
				        	  layer.msg("系统繁忙，购买失败！请稍后再试！",{icon:1,time:3000});
				          }else{
				            if (ss==1){
				                location.href="/api/<?php echo $UsersID;?>/pintuan/vorder/"+res+"/";
				            }else if(ss==0){
				               location.href="/api/<?php echo $UsersID;?>/pintuan/order/"+res+"/";
				            }
				          }
					  }
			      }, 'json'); 	
     });  

    //团购
    $('#tuangou, #prices').click(function(){
		      var goodsid=<?php echo $productsid;?>;
		      var orderstype=<?php echo $orderstype;?>;
		      var UsersID = "<?php echo $UsersID;?>";
		      var userid =<?php echo $_SESSION[$UsersID.'User_ID'];?>;
		      var teamid = "<?php echo isset($_GET['teamid'])?$_GET['teamid']:0;  ?>";
		      var num='1';
		      var one=1;
		      var Draw=<?php echo empty($Draw)?0:$Draw;?>;	
		      var price = $('.t3').html().replace('团购:¥','');
  			 $.post('/api/<?php echo $UsersID;?>/pintuan/ajax/',{action:"addcart",goodsid:goodsid,orderstype:orderstype,UsersID:UsersID,userid:userid,num:num,Draw:Draw,one:one,teamid:teamid},function(data){
							  if(data.status==0){
								  layer.msg(data.msg,{icon:1,time:3000},function(){
	                        		  window.location.href="/api/<?php echo $UsersID;?>/pintuan/";
	                              });
								  return false;
							  }else{
								  var res=data.id;
							      var ss=data.v;
    					          if(res==''){
    					        	  layer.msg("系统繁忙，购买失败！请稍后再试！",{icon:1,time:3000});
    					          }else{
    					            if (ss==1){
    					                location.href="/api/<?php echo $UsersID;?>/pintuan/vorder/"+res+"/";
    					            }else if(ss==0){
    					               location.href="/api/<?php echo $UsersID;?>/pintuan/order/"+res+"/";
    					            }
    					          }
							  }
					      }, 'json');
    });
</script>
</body>
</html>
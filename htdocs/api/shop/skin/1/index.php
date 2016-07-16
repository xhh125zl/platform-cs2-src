<?php
$json=json_decode($rsSkin['Home_Json'],true);
include("skin/top.php"); 
?>
<body>
<?php
ad($UsersID, 1, 1);
?>
<div id="shop_page_contents">
 <div id="cover_layer"></div>
 <link href='/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/page.css' rel='stylesheet' type='text/css' />
 <link href='/static/js/plugin/flexslider/flexslider.css' rel='stylesheet' type='text/css' />
 <script type='text/javascript' src='/static/js/plugin/flexslider/flexslider.js'></script>
 <script type='text/javascript' src='/static/api/shop/js/index.js'></script>
 <script language="javascript">
  var shop_skin_data=<?php echo json_encode($json) ?>;
 </script>
 <?php echo '<script type="text/javascript" src="/static/js/plugin/swipe/swipe.js"></script>' ?> 
 <div id="shop_skin_index">
  <?php
  if(isset($json)){
	  $is = 1;
	  foreach($json as $key=>$value){
		$url=explode('|',$value['url']);
		$pic=explode('|',$value['pic']);
		$txt=explode('|',$value['txt']);
		if($value['type']=='p1'){//一行两列
			$txtColor=explode('|',$value['txtColor']);
			$bgColor=explode('|',$value['bgColor']);
			echo '<div class="packagep1Sprite wrap_content">
		  <div class="wrapP1Item" style="margin:0;">
			<div class="p1Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
			if(!empty($txt[0])){
				echo '<div class="p1Word" style="color:'.$txtColor[0].'; background:'.$bgColor[0].';"><a href="'.$url[0].'" style="color:'.$txtColor[0].';">'.$txt[0].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="wrapP1Item">
			<div class="p1Img"><a href="'.$url[1].'"><img src="'.$pic[1].'" width="100%" /></a>';
			if(!empty($txt[1])){
				echo'<div class="p1Word" style="color:'.$txtColor[1].'; background:'.$bgColor[1].';"><a href="'.$url[1].'" style="color:'.$txtColor[1].';">'.$txt[1].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="clean"></div>
		</div>';
		}elseif($value['type']=='p0'){//一行三列
			$txtColor=explode('|',$value['txtColor']);
			$bgColor=explode('|',$value['bgColor']);
			echo '<div class="packagep0Sprite wrap_content">
		  <div class="wrapP0Item" style="margin:0;">
			<div class="p0Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
			if(!empty($txt[0])){
				echo '<div class="p0Word" style="color:'.$txtColor[0].'; background:'.$bgColor[0].';"><a href="'.$url[0].'" style="color:'.$txtColor[0].';">'.$txt[0].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="wrapP0Item">
			<div class="p0Img"><a href="'.$url[1].'"><img src="'.$pic[1].'" width="100%" /></a>';
			if(!empty($txt[1])){
				echo '<div class="p0Word" style="color:'.$txtColor[1].'; background:'.$bgColor[1].';"><a href="'.$url[1].'" style="color:'.$txtColor[1].';">'.$txt[1].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="wrapP0Item">
			<div class="p0Img"><a href="'.$url[2].'"><img src="'.$pic[2].'" width="100%" /></a>';
			if(!empty($txt[2])){
				echo '<div class="p0Word" style="color:'.$txtColor[2].'; background:'.$bgColor[2].';"><a href="'.$url[2].'" style="color:'.$txtColor[2].';">'.$txt[2].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="clean"></div>
		</div>';
		}elseif($value['type']=='p6'){//一行四列
			$txtColor=explode('|',$value['txtColor']);
			$bgColor=explode('|',$value['bgColor']);
			echo '<div class="packagep6Sprite wrap_content">
		  <div class="wrapP6Item" style="margin:0;">
			<div class="p6Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
			if(!empty($txt[0])){
				echo '<div class="p6Word" style="color:'.$txtColor[0].'; background:'.$bgColor[0].';"><a href="'.$url[0].'" style="color:'.$txtColor[0].';">'.$txt[0].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="wrapP6Item">
			<div class="p6Img"><a href="'.$url[1].'"><img src="'.$pic[1].'" width="100%" /></a>';
			if(!empty($txt[1])){
				echo '<div class="p6Word" style="color:'.$txtColor[1].'; background:'.$bgColor[1].';"><a href="'.$url[1].'" style="color:'.$txtColor[1].';">'.$txt[1].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="wrapP6Item">
			<div class="p6Img"><a href="'.$url[2].'"><img src="'.$pic[2].'" width="100%" /></a>';
			if(!empty($txt[2])){
				echo '<div class="p6Word" style="color:'.$txtColor[2].'; background:'.$bgColor[2].';"><a href="'.$url[2].'" style="color:'.$txtColor[2].';">'.$txt[2].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="wrapP6Item">
			<div class="p6Img"><a href="'.$url[3].'"><img src="'.$pic[3].'" width="100%" /></a>';
			if(!empty($txt[3])){
				echo '<div class="p6Word" style="color:'.$txtColor[3].'; background:'.$bgColor[3].';"><a href="'.$url[3].'" style="color:'.$txtColor[3].';">'.$txt[3].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="clean"></div>
		</div>';
		}elseif($value['type']=='p7'){//一行五列
			$txtColor=explode('|',$value['txtColor']);
			$bgColor=explode('|',$value['bgColor']);
			echo '<div class="packagep7Sprite wrap_content">
		  <div class="wrapP7Item" style="margin:0;">
			<div class="p7Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
			if(!empty($txt[0])){
				echo '<div class="p7Word" style="color:'.$txtColor[0].'; background:'.$bgColor[0].';"><a href="'.$url[0].'" style="color:'.$txtColor[0].';">'.$txt[0].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="wrapP7Item">
			<div class="p7Img"><a href="'.$url[1].'"><img src="'.$pic[1].'" width="100%" /></a>';
			if(!empty($txt[1])){
				echo '<div class="p7Word" style="color:'.$txtColor[1].'; background:'.$bgColor[1].';"><a href="'.$url[1].'" style="color:'.$txtColor[1].';">'.$txt[1].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="wrapP7Item">
			<div class="p7Img"><a href="'.$url[2].'"><img src="'.$pic[2].'" width="100%" /></a>';
			if(!empty($txt[2])){
				echo '<div class="p7Word" style="color:'.$txtColor[2].'; background:'.$bgColor[2].';"><a href="'.$url[2].'" style="color:'.$txtColor[2].';">'.$txt[2].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="wrapP7Item">
			<div class="p7Img"><a href="'.$url[3].'"><img src="'.$pic[3].'" width="100%" /></a>';
			if(!empty($txt[3])){
				echo '<div class="p7Word" style="color:'.$txtColor[3].'; background:'.$bgColor[3].';"><a href="'.$url[3].'" style="color:'.$txtColor[3].';">'.$txt[3].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="wrapP7Item">
			<div class="p7Img"><a href="'.$url[4].'"><img src="'.$pic[4].'" width="100%" /></a>';
			if(!empty($txt[3])){
				echo '<div class="p7Word" style="color:'.$txtColor[4].'; background:'.$bgColor[4].';"><a href="'.$url[4].'" style="color:'.$txtColor[4].';">'.$txt[4].'</a></div>';
			}
			echo '</div>
		  </div>
		  <div class="clean"></div>
		</div>';
		}elseif($value['type']=='p8'){//左一右二
			$txtColor=explode('|',$value['txtColor']);
			$bgColor=explode('|',$value['bgColor']);
			echo '<div class="packagep8Sprite wrap_content">
			<div class="left8">
		  <div class="wrapP8Item">
			<div class="p8Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  </div>
		  <div class="right8">
		  <div class="wrapP8Item">
			<div class="p8Img"><a href="'.$url[1].'"><img src="'.$pic[1].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  <div class="wrapP8Item">
			<div class="p8Img"><a href="'.$url[2].'"><img src="'.$pic[2].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  </div>
		  <div class="clean"></div>
		</div>';
		}elseif($value['type']=='p9'){//左一右四
			$txtColor=explode('|',$value['txtColor']);
			$bgColor=explode('|',$value['bgColor']);
			echo '<div class="packagep9Sprite wrap_content">
			<div class="left9">
		  <div class="wrapP9Item">
			<div class="p9Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  </div>
		  <div class="right9">
		  <div class="wrapP9Item">
			<div class="p9Img"><a href="'.$url[1].'"><img src="'.$pic[1].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  <div class="wrapP9Item">
			<div class="p9Img"><a href="'.$url[2].'"><img src="'.$pic[2].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  <div class="wrapP9Item">
			<div class="p9Img"><a href="'.$url[3].'"><img src="'.$pic[3].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  <div class="wrapP9Item">
			<div class="p9Img"><a href="'.$url[4].'"><img src="'.$pic[4].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  <div class="clean"></div>
		  </div>
		  <div class="clean"></div>
		</div>';
		}elseif($value['type']=='p10'){//左一右二
			$txtColor=explode('|',$value['txtColor']);
			$bgColor=explode('|',$value['bgColor']);
			echo '<div class="packagep10Sprite wrap_content">
			<div class="left10">
		  <div class="wrapP10Item">
			<div class="p10Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
			echo '</div>
		  </div>
		  <div class="wrapP10Item">
			<div class="p10Img"><a href="'.$url[1].'"><img src="'.$pic[1].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  </div>
		  <div class="right10">
		  <div class="wrapP10Item">
			<div class="p10Img"><a href="'.$url[2].'"><img src="'.$pic[2].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  </div>
		  <div class="clean"></div>
		</div>';
		}elseif($value['type']=='p11'){//左一右四
			$txtColor=explode('|',$value['txtColor']);
			$bgColor=explode('|',$value['bgColor']);
			echo '<div class="packagep11Sprite wrap_content">
			<div class="left11">
		  <div class="wrapP11Item">
			<div class="p11Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  <div class="wrapP11Item">
			<div class="p11Img"><a href="'.$url[1].'"><img src="'.$pic[1].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  <div class="wrapP11Item">
			<div class="p11Img"><a href="'.$url[2].'"><img src="'.$pic[2].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  <div class="wrapP11Item">
			<div class="p11Img"><a href="'.$url[3].'"><img src="'.$pic[3].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  <div class="clean"></div>
		  </div>
		  <div class="right11">
		  <div class="wrapP11Item">
			<div class="p11Img"><a href="'.$url[4].'"><img src="'.$pic[4].'" width="100%" /></a>';
			
			echo '</div>
		  </div>
		  </div>
		  <div class="clean"></div>
		</div>';
		}elseif($value['type']=='p2'){//文字
			$txt[0] = str_replace('&quot;','"',$txt[0]);
			echo '<div class="packagep2Sprite wrap_content">
		  <div class="p2word">'.$txt[0].'</div>
		</div>';
		}elseif($value['type']=='p3'){//图片
			$txtColor=explode('|',$value['txtColor']);
			$bgColor=explode('|',$value['bgColor']);
			echo '<div class="packagep3Sprite wrap_content">
		  <div class="wrapP3Item">
			<div class="p3Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
			if(!empty($txt[0])){
				echo '<div class="p3Word" style="color:'.$txtColor[0].'; background:'.$bgColor[0].';"><a href="'.$url[0].'" style="color:'.$txtColor[0].';">'.$txt[0].'</a></div>';
			}
			echo '</div>
		  </div>
		</div>';
		}elseif($value['type']=='p4'){//幻灯片
			echo '<div id="p4_'.$rsSkin['Home_ID'].$is.'" class="packagep4Sprite wrap_content">
		  <ul>';
		  for($i=0;$i<count($pic);$i++){
			  if($pic[$i]<>'' && $pic[$i]<>'undefined'){
				  echo '<li><a href="'.$url[$i].'"><img src="'.$pic[$i].'" alt="'.($i+1).'" style="width:100%;vertical-align:top;"/></a></li>';
			  }
		  }
		  echo '</ul>
		</div>
		<script>$(function(){new Swipe(document.getElementById("p4_'.$rsSkin['Home_ID'].$is.'"), {speed:500,auto:3000})});</script>';
		}elseif($value['type']=='p5'){//电话拨号
			echo '<ul style="background:'.$value['bgColor'].'" class="packagep5Sprite wrap_content">';
			$tels=explode('<br>',$value['txt']);
			foreach($tels as $k=>$v){
				$tel=explode('/',$v);
				echo '<li style="'.(count($tels)>1?'':'width:100%').'"><a style="color:'.$value['txtColor'].'; font-size:'.$value['fontSize'].'px;" href="tel:'.$tel[0].'">'.$tel[0].(count($tel)>1 ? '('.$tel[1].')' :'').'</a></li>';
			}
			echo '</ul>';
		}elseif($value['type']=='p12'){//搜索框
			$txtColor=explode('|',$value['txtColor']);
			$bgColor=explode('|',$value['bgColor']);
			echo '<div class="packagep12Sprite wrap_content">
		  <div class="wrapP12Item" style="background:'.$bgColor[0].'"><form action="/api/shop/search.php" method="get">
        <input type="text" name="kw" class="input" style="border:1px solid '.$txtColor[0].'" value="" placeholder="输入商品名称..." />
		<input type="hidden" name="UsersID" value="'.$UsersID.'" />
		<input type="hidden" name="OwnerID" value="'.($owner['id'] != '0' ? $owner['id'] : '').'" />
        <input type="submit" class="submit" value=" " style="background:url('.$pic[0].') no-repeat left top;" />
      </form>
		  </div>
		</div>';
		}
		$is++;
	  }
	  
  }
?>
 </div>
</div>
<div id="back-to-top"></div>
<?php require_once('skin/distribute_footer.php'); ?>
<script>  
	$(function(){  
		//当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失  
		$(function () {  
			$(window).scroll(function(){  
				if ($(window).scrollTop()>100){  
					$("#back-to-top").fadeIn(1500);  
				}  
				else  
				{  
					$("#back-to-top").fadeOut(1500);  
				}  
			});  
	  
			//当点击跳转链接后，回到页面顶部位置  
	  
			$("#back-to-top").click(function(){  
				$('body,html').animate({scrollTop:0},1000);  
				return false;  
			});  
		});  
	});  
</script>
<!--懒加载--> 
<script type='text/javascript' src='/static/js/plugin/lazyload/jquery.scrollLoading.js'></script> 
<script language="javascript">
	$("img").scrollLoading();
</script>
</body>
</html>
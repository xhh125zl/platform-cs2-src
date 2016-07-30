<link href='/static/api/shop/skin/default/page.css' rel='stylesheet' type='text/css' />
<?php 
  if(!empty($goodslist)){ 
      foreach($goodslist as $k => $v){
          if($v['goodsNum']>0){
?>
<div class="ind_wrap">
  <div class="category">
      <h3><a href="/api/<?=$v['Users_ID'] ?>/<?=$v["module"]?>/act_<?=$v['Active_ID']?>/" class="more">查看更多</a><?=$v["Active_Name"] ?></h3>
  </div>
  <div class="products">
<?php
              foreach($v['goods'] as $key => $value){
                  $imgpath = json_decode($value['Products_JSON'],true);
?>
        <div class="items">
            <div class="pro_img">
                <a href="/api/<?=$v['Users_ID'] ?>/<?=$v["module"]?>/act_<?=$v['Active_ID']?>/">
                    <img data-url="<?=$imgpath['ImgPath'][0] ?>" src="<?=$imgpath['ImgPath'][0] ?>"/>
                </a>
            </div>
            <div class="name">
                <a href="/api/<?=$v['Users_ID'] ?>/<?=$v["module"]?>/act_<?=$v['Active_ID']?>/"><?=$value["Products_Name"] ?></a>
            </div>
        </div>
<?php         
        if($v['goodsNum']-1==$key){
?>            
            <div class="clear"></div>
<?php
        }
              }
?>
   </div>
</div>
<?php 
          }
      } 
  }
?>


	<div id="shop_info">
      <span></span>
      <span><?php echo $rsConfig["ShopName"];?></span>
      <span class="allcategory"><a href="/api/<?php echo $UsersID;?>/shop/allcategory/"><img src="/static/api/shop/skin/default/images/allcategory.png" /> 全部分类</a></span>
	  <span class="qrcode"><a href="/api/<?php echo $UsersID;?>/distribute/qrcodehb/"><img src="/static/api/shop/skin/default/images/qrcode.png" /> 二维码</a></span>
      <span class="favourite"><a href="/api/<?php echo $UsersID;?>/shop/member/favourite/"><img src="/static/api/shop/skin/default/images/favourite.png" /> 收藏</a></span>
      <img src="<?=$rsConfig["ShopLogo"]?>" class="shop_img" />
      <div class="clear"></div>
    </div>
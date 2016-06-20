	  <form action="/api/shop/search.php" method="get">
        <input type="text" name="kw" class="input" value="" placeholder="输入商品名称..." />
		<input type="hidden" name="UsersID" value="<?php echo $UsersID;?>" />
		<input type="hidden" name="OwnerID" value="<?php echo $owner['id'] != '0' ? $owner['id'] : '';?>" />
        <input type="submit" class="submit" value=" " />
      </form>
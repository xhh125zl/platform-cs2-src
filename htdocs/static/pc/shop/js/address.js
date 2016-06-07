var address_obj={
	address_init:function(){
		//关闭弹窗
		$('.cut').click(function(e) {
			$('.box_dizhi_form').fadeOut(200);
			$('.edit_dizhi_form').fadeOut(200);
		});
		//添加地址弹窗
		$('.add_dizhi').click(function(e) {
			$('.box_dizhi_form input[name=name]').val('');
			showLocation(0, 0, 0);
			$('.box_dizhi_form input[name=detailed]').val('');
			$('.box_dizhi_form input[name=mobile]').val('');
			$('.box_dizhi_form input[name=addr_id]').val('');
			$('.box_dizhi_form').fadeIn(200);
		});
		//删除地址
		$('.dizhi .cut').click(function(e){
			var that = this;
			$.post(shop_ajax_url, {action:'del_address',addr_id:$(that).parent('.dizhi').attr('addr_id')}, function(data){
				if(data.status == 1) {
					$(that).parent('.dizhi').remove();
				}
			}, 'json');
		});
		//保存（新增、编辑）
		$('.saveaddress').click(function(){
			var that = this;
			var post_date = $('.box_dizhi_form form').serialize()+'&action=save_address';
			$.post(shop_ajax_url, post_date, function(data){
				if(data.status == 1) {
					$('.box_dizhi_form').fadeOut(200);
					if(data.url){
					    location.href = data.url;
					}else{
					    location.reload();
					}
					
				}else {
					alert(data.msg);
				}
			}, 'json');
		});
		//编辑拉取信息
		$('a[fxy_type=edit_address]').click(function(){
			$('.box_dizhi_form').fadeIn(200);
			$.post(shop_ajax_url, {action:'get_address',addr_id:$(this).parents('.dizhi').attr('addr_id')} ,function(data){
				if(data.status == 1) {
					$('.box_dizhi_form input[name=name]').val(data.info.Address_Name);
					showLocation(data.info.Address_Province, data.info.Address_City, data.info.Address_Area);
					$('.box_dizhi_form input[name=detailed]').val(data.info.Address_Detailed);
					$('.box_dizhi_form input[name=mobile]').val(data.info.Address_Mobile);
					$('.box_dizhi_form input[name=addr_id]').val(data.info.Address_ID);
				}
			}, 'json');
		});
		//设置默认地址
		$('a[fxy_type=set_address]').click(function(){
			$.post(shop_ajax_url, {action:'set_address',addr_id:$(this).parents('.dizhi').attr('addr_id')} ,function(data){
				if(data.status == 1) {
					if(data.url) {
						location.href = data.url;
					}else {
						location.reload();
					}
				}else {
					alert(data.msg);
				}
			}, 'json');
		});
	},
}
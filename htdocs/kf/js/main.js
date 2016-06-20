var main_obj={
	page_init:function(){
		if(self.location!=top.location){
			top.location=self.location;
		}
		$('body, html').css('overflow', 'hidden');
		$('a').click(function(){
			this.blur();
		});
		$('#header a').click(function(){
			$('#main .menu dt').removeClass('cur');
			$('#main .menu dd').hide();
			$('#main .menu div').removeClass('cur');
			main_obj.page_scroll_init();
		});
		$('#main .menu a').click(function(){
			$('#main .menu div').removeClass('cur');
			$(this).parent().addClass('cur');
		});
		
		$('#main').height($(window).height()-$('#header').height()-$('#footer').height());
		var w=$(window).width()-220;
		w=w<780?780:w;
		$('#main .iframe').width(w);
		main_obj.page_scroll_init();
	},
	
	page_scroll_init:function(){
		$('#main .menu').jScrollPane();
	},
	
	showUser : function(){
		setInterval(function(){
			$.ajax({
				type	: "POST",
				url		: "/kf/admin/userlist.php",
				data	: "UsersID="+UsersID+"&KfId="+KfId,
				dataType: "json",
				success	: function(msg){
					$("#UserList").html(msg["UserList"]);
					if(msg["UserId"] && chatto==0){
						chatto = 1;
						$("#chatto").attr("src","chat.php?UserId="+msg["UserId"]);
					}
				}
			});
		},3000);
	}
}
//前台砍价js


function timer(intDiff){
	
    window.setInterval(function(){
    var day=0,
        hour=0,
        minute=0,
        second=0;//时间默认值       
    if(intDiff > 0){
        day = Math.floor(intDiff / (60 * 60 * 24));
        hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
        minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
        second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
    }
    if (minute <= 9) minute = '0' + minute;
    if (second <= 9) second = '0' + second;
    $('#day_show').html(day);
    $('#hour_show').html(hour);
    $('#minute_show').html(minute);
    $('#second_show').html(second);
    intDiff--;
    }, 1000);
} 


var kanjia_obj = {
	
    activity_init: function() {
		
		
		this.general_init();
		//开启倒计时
		var intDiff = parseInt(time_interval);//倒计时总秒数量
		timer(intDiff);
		
        $("#clear_session").click(function() {

            var param = {
                action: 'clear_session'
            };

            $.post(base_url + 'api/kanjia/ajax.php?UsersID='+UsersID, param, function(data) {
				alert(data);
            });

        });
		
		$("#self_kan").click(function(){
			 var self_kaned = $("#self_kaned").val();
			 
			 if (self_kaned == 0) {
				$(this).addClass('disabled');
		        window.location = $(this).attr("href");
			  }
		});
        //邀请砍价 
        $("#invite_kan").click(function() {
            var self_kaned = $("#self_kaned").val();
            if (self_kaned == 0) {
                alert("请先自砍一刀参加此活动!");
            } else {
                $('.share_layer').css('height', $(document).height()).show();
                return false;

            }

        });

        //属性选择
        $('.desc .property span').click(function() {
            var PName = $(this).attr('PName');
            $('.desc .property span[PName=' + PName + ']').removeClass();
            $(this).addClass('cur');
            $('.desc #' + PName).val($(this).text());
        });

        //购买商品
        $("#buy_btn").click(function() {
            var self_kaned = $("#self_kaned").val();
            if (self_kaned == 0) {
                alert("请先自砍一刀参加此活动!");
            } else {
            	var params = $("form#addto_cart_form").serialize();
      
           		params = decodeURIComponent(params, true);
                $.post(base_url + "api/kanjia/ajax.php?UsersID=" + UsersID, params, function(data) {
                    
					if (data.status == 1) {
                    	var url = $("form#addto_cart_form").attr('action')+"buy/"+Kanjia_ID+"/";
                        window.location = url;
                    }
					

                }, 'json');
                //window.location.href = $(this).attr("href");
                //$.post()
                return false;
            }

        });


        $('.share_layer').click(function() {
            $(this).hide();
        });
    },
    help_init: function() {
		this.general_init();	
		$("#help_kan").click(function(){
			
			 
			
		        window.location = $(this).attr("href");
			  
		});
		
    },
    buy_init: function() {
		this.general_init();
        $("form#order_form input[name='AddressID']").click(function() {
            var address_id = $(this).val();
            if (address_id == 0) {
                $("#new_address_info").css({
                    display: 'block'
                });
            } else {
                $("#new_address_info").css({
                    display: 'none'
                });
            }
        });


        $("form#order_form input[name='Shipping[Express]']").click(function() {

            var shipping_price = parseFloat($(this).attr("price"));
            var total_price = parseFloat($("#total_price").val());
            var order_total = shipping_price + total_price;
            $("#shipping_price").attr("value", shipping_price);

            $("#order_sum").html(order_total);
        });

        $("#submit").click(function() {
            var AddressID = parseInt($('#order_form input[name=AddressID]:checked').val());
            if (AddressID == 0 || isNaN(AddressID)) {
                if (global_obj.check_form($('#order_form  *[notnull]'))) {
                    return false;
                }
            }
            var params = $("form#order_form").serialize() + '&Shipping\[Price\]=' + parseFloat($('#order_form input[name=shipping_price]').val());
            params = decodeURIComponent(params, true);

            $.post(base_url + "api/kanjia/ajax.php?UsersID=" + UsersID, params, function(data) {
               
				if (data.status == 1) {
                    window.location = data.url
                }
				

            }, 'json');

            //params = encodeURI(encodeURI(params));


        });


    },
	general_init:function(){
		
		$("#search_btn").click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$("#kanjia_search").submit();
		});

	},
	product_detail_init:function(){
		$('#detail .desc #tag0').click(function() {

            $('#detail .desc span').removeClass();

            $(this).addClass('cur');

            $('#description').show();

            $('#commit').hide()

        });

        $('#detail .desc #tag1').click(function() {

            $('#description').hide();

            $('#commit').show();

            $('#detail .desc span').removeClass();

            $(this).addClass('cur')

        });
		 
	},
}

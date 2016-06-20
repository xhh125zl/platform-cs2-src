/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var businesscard_obj={
	businesscard_init:function(){
		$('#ImgPathDetail').html(global_obj.img_link($('#businesscard_form input[name=ImgPath]').val()));
		$('#HeaderImgDetail').html(global_obj.img_link($('#businesscard_form input[name=HeaderImgPath]').val()));
		global_obj.map_init();
		$('#businesscard-list-type .item').removeClass('item_on').each(function(){
			$(this).click(function(){
				$('#businesscard-list-type .item').removeClass('item_on');
				$(this).addClass('item_on');
				$('#businesscard_form input[name=SkinID]').val($(this).attr('SkinId'));
				if($(this).attr('SkinId')==4){
					$('#bg').show();
					$('#face').hide();
				}else{
					$('#bg').hide();
					$('#face').show();
				}
			});
		}).filter('[SkinId='+$('#businesscard_form input[name=SkinID]').val()+']').addClass('item_on');
		$('#businesscard_form').submit(function(){return false;});
		$('#businesscard_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#businesscard_form').serialize(), function(data){
				if(data.status==1){
					window.location='./index.php';
				}else{
					alert(data.msg);
				}
				$('#businesscard_form input:submit').attr('disabled', false);
			}, 'json');
		});
	}
}
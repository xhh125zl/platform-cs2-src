/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var businesscard_obj={
	businesscard_init:function(){
		$('#ImgPathDetail').html(global_obj.img_link($('#businesscard_form input[name=ImgPath]').val()));
		$('#HeaderImgDetail').html(global_obj.img_link($('#businesscard_form input[name=HeaderImgPath]').val()));
		global_obj.map_init();
		
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
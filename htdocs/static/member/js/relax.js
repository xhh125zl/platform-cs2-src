var relax_obj={
	index_init:function(){
		$('#relax img[field]').addClass('pointer').click(function(){
			var img_obj=$(this);
			$.get('?action=item', 'field='+img_obj.attr('field')+'&Status='+img_obj.attr('Status'), function(data){
				if(data.status==1){
					var img=img_obj.attr('Status')==0?'on':'off';
					img_obj.attr('src', '/static/member/images/ico/'+img+'.gif');
					img_obj.attr('Status', img_obj.attr('Status')==0?1:0);
				}else{
					alert('设置失败，出现未知错误！');
				}
			}, 'json');
		});
	}
}
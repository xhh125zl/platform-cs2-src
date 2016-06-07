

var reserve_obj={
	reserve_view_init:function(){
		$('#reserve a[ReserveID]').click(function(){
			$('#reserve_view').leanModal();
			$('#reserve_view .contents').html('<div class="loading"><img src="/static/member/images/ico/loading.gif"></div>');
			
			$.get('?action=reserve_view&ReserveID='+$(this).attr('ReserveID'), function(data){
				$('#reserve_view .contents').html(data);
			}, 'text');
			return false;
		});
	},
	
	reserve_edit_init:function(){
		global_obj.map_init();
		global_obj.reserve_form_init();
		
		$('#reserve_form').submit(function(){return false;});
		$('#reserve_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#reserve_form').serialize(), function(data){
				if(data.status==1){
					window.location='reserve.php';
				}else{
					alert(data.msg);
					$('#reserve_form input:submit').attr('disabled', false);
				}
			}, 'json');
		})
	}
}
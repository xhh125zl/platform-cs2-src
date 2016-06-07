/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var battle_obj={		
	exam_init:function(){		
		$('#exam_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#exam_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	activity_init:function(){
		var date_str=new Date();
		$('#battle_form input[name=Time]').daterangepicker({
			timePicker		: true,
			format			: 'YYYY/MM/DD HH:mm:00'
		});	
		
		global_obj.file_upload($('#MusicUpload'), $('#battle_form input[name=MusicPath]'), $('#MusicUpload'), 'battle', false, 1, function(filename,filepath){
			$("#battle_form input[name=BackgroundMusic]").val(filename);
			$("#battle_form input[name=MusicPath]").val(filepath);
		},'*.mp3');
		global_obj.file_upload($('#ImgUpload'), $('#battle_form input[name=ImgPath]'), $('#ImgDetail'), 'battle');
		$("#ImgDetail").html(global_obj.img_link($("#battle_form input[name=ImgPath]").val()));
		
		$('#battle_form').submit(function(){return false;});
		$('#battle_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#battle_form').serialize(), function(data){
				if(data.status==1){
					window.location='battle.php';
				}else{
					alert(data.msg);
					$('#battle_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	}
}
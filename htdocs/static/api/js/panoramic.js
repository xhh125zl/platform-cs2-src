/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var panoramic_obj={
	panoramic_init:function(){
		$('#container').css({
			width:$(window).width(),
			height:$(window).height()
		});
		pano=new pano2vrPlayer('container');
		pano.readConfigUrl($('input[name=xml]').val());	//不能用二级域名访问的方式
		
		$('#footer li').css({width:100/$('#footer li').size()-0.001+'%'}).children('div').data('display', '0').click(function(){
			$('#footer dl').slideUp(100);
			if($(this).data('display')==0){
				$(this).siblings('dl').slideDown(100);
				$('#footer li div').data('display', '0');
				$(this).data('display', '1');
			}else{
				$('#footer li div').data('display', '0');
			}
		});
		$('#footer a').each(function(){
			$(this).click(function(){
				$('#footer dl').slideUp(100);
				$('#footer li div').data('display', '0');
			});
		});
	}
}
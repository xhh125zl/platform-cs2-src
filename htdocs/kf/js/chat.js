chat = {
	UserId : "",
	KfId: "",
	UsersID : "",
	sendType : true, //发送信息开关，防止用户快速提交,3秒后随着showmsg操作自动更新状态
	init : function(){
		chat.UserId = $("#UserId").val();
		chat.UsersID = $("#UsersID").val();
		chat.KfId = $("#KfId").val();
		chat.ctrlFace(); //表情控制
		chat.setWindowScrollTop();//设置滚动条
		chat.setParentNum(chat.UserId);//设置父级窗口用户列表未查看信息数目
		chat.sendMsg();//信息发送信息
		chat.quickSend();//组合键发送信息
		chat.showMsg();//每3秒获取信息
		chat.ctrlSource();//管理常用语及聊天记录操作
	},
	
	cancelSendImg : function(){ //取消发送图片
		$(".reply_frame").html("");	
		$("#SendTyp").val("0");
		$(".reply_frame").attr("contenteditable","true");
		$(".reply_cancel").hide();
	},
	sendImg : function(){
		$("#Upload_form").submit();
	},
	addImg : function(fileName,dateTime){
		var obj = $(".list_frame",window.parent.document);
		var img = "<img class='img' src='"+fileName+"' width='130' />";
		$("#SendTyp",window.parent.document).val("1");//更改编辑状态为1图片
		$(".reply_frame" ,window.parent.document).html(img);
		$(".reply_frame" ,window.parent.document).attr("contenteditable","false");
		$(".reply_cancel",window.parent.document).css("display","block")
	},
	showMsg : function(){
		setInterval(function(){
			$.ajax({
				type	: "POST",
				url		: "/kf/web/ajax.php",
				data	: "type=show&UserId="+chat.UserId+"&KfId="+chat.KfId,
				dataType: "json",
				success	: function(msg){
					if(msg["status"] == 1){
						$(".list_frame").html(msg["MsgList"]);
						chat.setWindowScrollTop();
					}
				}
			});
			chat.sendType = true;
		},3000);
	},
	quickSend:function(){ 
		$(document).keydown(function(e){
			var oEvent = window.event||e;
            if (oEvent.keyCode == 13 && oEvent.ctrlKey) {  
				if(!$(".reply_frame").html()){
					alert("请输入留言内容");
					return false;
				}
				chat.addMsg();
				$(".reply_frame").html("");
            }
		});	
	},
	sendMsg : function(){
		$(".reply_btn").click(function(){
			if(!$(".reply_frame").html()){
				alert("请输入留言内容");
				return false;
			}
			
			chat.addMsg();
			$(".reply_frame").html(""); //清空留言框
		});
	},
	addMsg : function(){
		if($("#SendTyp").val() == "0"){  //文字状态
			$(".reply_frame img").each(function(index, element) {
				var sTitle = $(element).attr("title");
				if(sTitle){
					$(element).after("<span style='display:none'>[face"+sTitle+"]</span>");
				}
			});
			content = $(".reply_frame").text(); //防止用户复制html到文本框里面
			content = content.replace(/&/g,"|*|");
		} else {
			//图片形式提交获取src
			content = $(".reply_frame").find("img").attr("src");
		}
		if(chat.sendType){ //为真发送信息
			chat.sendType = false;
			$.ajax({
				type	: "POST",
				url		: "/kf/web/ajax.php",
				data	: "type=add&Msg="+content+"&UserId="+chat.UserId+"&KfId="+chat.KfId+"&SendTyp="+$("#SendTyp").val()+"&date="+new Date(),
				dataType: "json",
				success	: function(msg){
					if(msg["status"] == 1){
						$(".list_frame").append(msg["MsgList"]);
						chat.setWindowScrollTop();
						$("#SendTyp").val("0");
						$(".reply_frame").attr("contenteditable","true");
						$(".reply_cancel").hide();
					} else {
						$("#msg"+msg["MId"]+" .message_item").html("添加失败");
					}
				}
			});
		} else {
			alert('信息发送中，请等待');
		}
	},
	setParentNum:function(uid){
		var list = $("#list"+chat.UserId,window.parent.document);
		list.find(".visitors_num").html(0); //代表已经查看了信息数目
	},
	ctrlFace : function(){ //表情管理
		var tt = null;
		$("#face_icon").click(function(){
			var stau = $(".face_frame").css("display");
			if(stau == "none"){
				frmaeHid(1);
			} else {
				frmaeHid(0);
			}
		});
		$("#face_icon").mouseout(function(){
			tt = setTimeout(function(){
				frmaeHid(0);
			},300);
		});
		$(".face_frame").mouseleave(function(){
			frmaeHid(0);
		});
		$(".face_frame").mouseover(function(){
            clearTimeout(tt);
        });
		//---- 点击事件 ------//
		$(".face_frame li").click(function(){
			var img=$(this).find("img").attr("src");
			var num=$(this).attr("num");  
			$(".reply_frame").focus();
			var img_url = "<img title=\""+num+"\" src='"+img+"' />";
			if($("#SendTyp").val() == "0"){
				_insertimg(img_url);
			}
			frmaeHid(0);
		});
		//---- 闭包函数管理表情及光标位置获取start ------//
		function frmaeHid(num)
		{
			if(num == 1)
			{
				$(".face_frame").show();
				$(".list_frame embed").css("visibility","hidden");
			} else {
				$(".face_frame").hide();
				$(".list_frame embed").css("visibility","visible");
			}
		}
		function _insertimg(str)
		{
			var selection= window.getSelection ? window.getSelection() : document.selection;
			var range= selection.createRange ? selection.createRange() : selection.getRangeAt(0);
			if (!window.getSelection) //ie
			{
				var oFrame = $(".reply_frame").get(0);
				oFrame.focus();
				var selection= window.getSelection ? window.getSelection() : document.selection;
				var range= selection.createRange ? selection.createRange() : selection.getRangeAt(0);
				range.pasteHTML(str);
				range.collapse(false);
				range.select();
			} else {
				var oFrame = $(".reply_frame").get(0);
				oFrame.focus();
				range.collapse(false);
				var hasR = range.createContextualFragment(str);
				var hasR_lastChild = hasR.lastChild;
				while (hasR_lastChild && hasR_lastChild.nodeName.toLowerCase() == "br" && hasR_lastChild.previousSibling && hasR_lastChild.previousSibling.nodeName.toLowerCase() == "br") 
				{
					var e = hasR_lastChild;
					hasR_lastChild = hasR_lastChild.previousSibling;
					hasR.removeChild(e)
				}                                
				range.insertNode(hasR);
				if (hasR_lastChild)
				{
					range.setEndAfter(hasR_lastChild);
					range.setStartAfter(hasR_lastChild)
				}
				selection.removeAllRanges();
				selection.addRange(range)
			}
		}
		//---- 闭包函数管理表情及光标位置获取end ------//
	},
	setWindowScrollTop : function(){ //设置浏览器高度	
		$(".list_frame").scrollTop(1000000);
	}
}